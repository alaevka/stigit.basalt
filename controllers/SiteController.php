<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Transactions;

class SiteController extends Controller
{

    private $_podr_data_array = [];
    private $_multidemensional_podr;
    private $_multidemensional_podr_agreed;
    private $_multidemensional_podr_transmitted;
    private $dateFormat = 'YYYY-MM-DD hh24:mi:ss';
    const _UNDEFINED = '----------- не задано -----------';
    /*
        Метод описания поведений для доступа пользователя
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['logout', 'index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /*
        Определение действий для обработки ошибок и формирования капчи (если необходимо)
    */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /*
        Метод формирования индексной страницы
    */
    public function actionIndex()
    {
        $model = new \app\models\IssueForm;
        
        $this->_podr_data_array = $this->_getPodrData();
        $this->_createPodrTree(1, 0);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            // echo '<pre>';
            // print_r($model); die();
            
            if($model->validate()) {

                $transactions = \app\models\Transactions::find()->where(['TN' => \Yii::$app->user->id ])->orderBy('ID DESC')->one();
                /*
                    Сохраняем модель TASKS
                */
                $task = new \app\models\Tasks;
                if(empty($model->documentid)) {
                    $task->DESIGNATION = $model->designation;
                    
                } else {
                    $query = new \yii\db\Query;
                    $query->select('DESIGNATION AS name')
                            ->from('STIGIT.V_PRP_DESIGNATION')
                            ->where('DOCUMENTID = \'' . $model->documentid .'\'');
                    $command = $query->createCommand();
                    $data = $command->queryOne();
                    $task->DESIGNATION = $data['name'];
                    $task->DOCUMENTID = $model->documentid;
                }
                
                $task->TASK_NUMBER = $model->task_number;
                $task->ORDERNUM = $model->ordernum;
                $task->PEOORDERNUM = $model->peoordernum;
                $task->TASK_TEXT = $model->message;
                $task->DEADLINE = new \yii\db\Expression("to_date('" . $model->date . "','{$this->dateFormat}')");
                $task->TRACT_ID = $transactions->ID;
                

                //print_r($model->podr_list); die();

                if($task->save()) {
                    /*
                        Сохраняем модель PODR_TASKS
                    */
                    foreach(explode(',', $model->podr_list) as $podr) { 
                        $podr_task = new \app\models\PodrTasks;
                        $podr_task->TASK_ID = $task->ID;
                        $podr_task->KODZIFR = $podr;
                        $podr_task->TRACT_ID = $transactions->ID;
                        $podr_task->save();
                    }
                    /*
                        Сохраняем модель PERS_TASKS
                    */
                    if(!empty($model->persons_list)) {
                        foreach(explode(',', $model->persons_list) as $person) {
                            $person_task = new \app\models\PersTasks;
                            $person_task->TASK_ID = $task->ID;
                            $person_task->TN = $person;
                            $person_task->TRACT_ID = $transactions->ID;
                            $person_task->save();
                        } 
                    }
                    \Yii::$app->getSession()->setFlash('flash_message_success', 'Задание выдано');
                    return $this->redirect(['index']);
                } else {
                    //print_r($task->errors); die();
                    \Yii::$app->getSession()->setFlash('flash_message_error', 'Что-то пошло не так. Обратитесь к администратору.');
                    return $this->redirect(['index']);
                }
            } else {
                \Yii::$app->getSession()->setFlash('flash_message_error', 'Что-то пошло не так. Обратитесь к администратору.');
                return $this->redirect(['index']);
            }
        }        

        $searchModel = new \app\models\SearchTasks;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination->pageSize=15;

        return $this->render('index', [
            'podr_data' => $this->_multidemensional_podr,
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function _checkNextPodrTree($parent_id) {
        if (isset($this->_podr_data_array[$parent_id])) { 
            return true;
        }
    }

    public function _createPodrTree($parent_id, $level) {
        if (isset($this->_podr_data_array[$parent_id])) { 
            $this->_multidemensional_podr .= "<ul>";
            foreach ($this->_podr_data_array[$parent_id] as $value) {
                //$this->_multidemensional_podr .= "<li><input id=\"checkbox_".$value['id']."\" type='checkbox' name=\"podr_check[]\" data-title=\"".$value['name']."\" value=\"".$value['id']."\"> <span><label for=\"checkbox_".$value['id']."\">" . $value['name'] . "</label></span></li>";
                //check next
                if($this->_checkNextPodrTree($value['id'])) {
                    $class = "class=\"collapsed\"";
                } else {
                    $class = '';
                }

                $this->_multidemensional_podr .= "<li ".$class."><input id=\"checkbox_".$value['code']."\" style=\"display: none;\" type='checkbox' name=\"podr_check[]\" data-title=\"".$value['name']."\" value=\"".$value['code']."\" /><span style=\"font-weight: normal; font-size: 11px;\" for=\"checkbox_".$value['code']."\"><a href=\"#\" data-id=\"".$value['code']."\" class=\"checkbox-podr-link\">".$value['name']."</a></span>";
                $level++;
                $this->_createPodrTree($value['id'], $level);
                $level--; 
            }
            $this->_multidemensional_podr .= "</ul>";
        }
    }

    public function _createPodrTreeAgreed($parent_id, $level) {
        if (isset($this->_podr_data_array[$parent_id])) { 
            $this->_multidemensional_podr_agreed .= "<ul>";
            foreach ($this->_podr_data_array[$parent_id] as $value) {
                if($this->_checkNextPodrTree($value['id'])) {
                    $class = "class=\"collapsed\"";
                } else {
                    $class = '';
                }

                $this->_multidemensional_podr_agreed .= "<li ".$class."><input id=\"checkbox_".$value['code']."\" style=\"display: none;\" type='checkbox' name=\"podr_check[]\" data-title=\"".$value['name']."\" value=\"".$value['code']."\" /><span style=\"font-weight: normal; font-size: 11px;\" for=\"checkbox_".$value['code']."\"><a href=\"#\" data-id=\"".$value['code']."\" class=\"checkbox-podr-link-agreed\">".$value['name']."</a></span>";
                $level++;
                $this->_createPodrTreeAgreed($value['id'], $level);
                $level--; 
            }
            $this->_multidemensional_podr_agreed .= "</ul>";
        }
    }

    public function _createPodrTreeTransmitted($parent_id, $level) {
        if (isset($this->_podr_data_array[$parent_id])) { 
            $this->_multidemensional_podr_transmitted .= "<ul>";
            foreach ($this->_podr_data_array[$parent_id] as $value) {
                if($this->_checkNextPodrTree($value['id'])) {
                    $class = "class=\"collapsed\"";
                } else {
                    $class = '';
                }

                $this->_multidemensional_podr_transmitted .= "<li ".$class."><input id=\"checkbox_".$value['code']."\" style=\"display: none;\" type='checkbox' name=\"podr_check[]\" data-title=\"".$value['name']."\" value=\"".$value['code']."\" /><span style=\"font-weight: normal; font-size: 11px;\" for=\"checkbox_".$value['code']."\"><a href=\"#\" data-id=\"".$value['code']."\" class=\"checkbox-podr-link-transmitted\">".$value['name']."</a></span>";
                $level++;
                $this->_createPodrTreeAgreed($value['id'], $level);
                $level--; 
            }
            $this->_multidemensional_podr_transmitted .= "</ul>";
        }
    }


    public function _getMulti(&$rs, $parent) {
        $out = array();
        if (!isset($rs[$parent]))
        {
            return $out;
        }
        foreach ($rs[$parent] as $row)
        {
            $chidls = $this->_getMulti($rs, $row['id']);
            if ($chidls)
            {
                //$row['expanded'] = false;
                $row['children'] = $chidls;            
            }
            $out[] = $row;
        }
        return $out;
    }

    public function _getPodrData($parent = 1, $i = 0) {
        $query = new \yii\db\Query;
        $query->select('NAIMPODR AS name, KODPODR AS id, KODRODIT as parent, KODZIFR as code')
                ->from('STIGIT.V_F_PODR')
                ->orderBy('NAIMPODR asc');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $return = array();
        foreach ($data as $value) {
            $return[$value['parent']][] = $value;
        }
        return $return;
    }

    public function actionDesignationsearch($q = null, $id = null) {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('DOCUMENTID AS id, DESIGNATION AS text, ORDERNUM AS ordernum, PEOORDERNUM AS peoordernum')
                ->from('STIGIT.V_PRP_DESIGNATION')
                ->where('LOWER(DESIGNATION) LIKE \'%' . mb_strtolower($q, 'UTF-8') .'%\'')
                ->limit(20);
                //echo mb_strtolower($q, 'UTF-8'); die();
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }

    public function actionOrdernumsearch($q = null, $id = null) {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('DOCUMENTID AS id, DESIGNATION AS designation, ORDERNUM AS text, PEOORDERNUM AS peoordernum')
                ->from('STIGIT.V_PRP_DESIGNATION')
                ->where('LOWER(ORDERNUM) LIKE \'%' . mb_strtolower($q, 'UTF-8') .'%\'')
                ->limit(20);
                //echo mb_strtolower($q, 'UTF-8'); die();
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }

    public function actionPeoordernumsearch($q = null, $id = null) {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('DOCUMENTID AS id, DESIGNATION AS designation, ORDERNUM AS ordernum, PEOORDERNUM AS text')
                ->from('STIGIT.V_PRP_DESIGNATION')
                ->where('LOWER(PEOORDERNUM) LIKE \'%' . mb_strtolower($q, 'UTF-8') .'%\'')
                ->limit(20);
                //echo mb_strtolower($q, 'UTF-8'); die();
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }


    public function actionGetpersons() {
        if (Yii::$app->request->isAjax) {
            $post_data = $_POST['selected_podr'];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $persons_list = '<ul>';
            foreach(json_decode($post_data) as $kodzifr => $value) {
                $persons_list .= "<li class=\"collapsed\"><span style=\"font-weight: normal; font-size: 12px;\">".$value."</span>";
                //get persons names
                $query = new \yii\db\Query;
                $query->select('*')
                    ->from('STIGIT.V_F_PERS')
                    ->where('KODZIFR = \'' . $kodzifr .'\'');
                $command = $query->createCommand();
                $data = $command->queryAll();
                $persons_list .= '<ul>';
                foreach ($data as $key => $value) {
                    $persons_list .= "<li><input id=\"checkbox_".$value['TN']."\" type='checkbox' name=\"persons_check[]\" data-title=\"".$value['FIO']."\" value=\"".$value['TN']."\" /> <span style=\"font-size: 11px;\">".$value['FAM']." ".$value['IMJ']." ".$value['OTCH']."</span></li>";
                }
                $persons_list .= '</ul>';
            }
            $persons_list .= '</ul>';
            return $persons_list;
        }
    }

    public function actionGetissuedata() {
        if (Yii::$app->request->isAjax) {
            $issue_id = $_POST['id'];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $issue = \app\models\Tasks::findOne($issue_id);
            
            

            $podr_tasks = \app\models\PodrTasks::find()->where(['TASK_ID' => $issue->ID])->all();
            if($podr_tasks) {
                $podr_list = '';
                foreach($podr_tasks as $task) {
                    $query = new \yii\db\Query;
                    $query->select('*')
                        ->from('STIGIT.V_F_PODR')
                        ->where('KODZIFR = \'' . trim($task->KODZIFR) .'\'');
                    $command = $query->createCommand();
                    $data = $command->queryOne();
                    if(isset($data['NAIMPODR']))
                        $podr_list .= $data['NAIMPODR']."<br>";
                }
               
            }
            $pers_tasks = \app\models\PersTasks::find()->where(['TASK_ID' => $issue->ID])->all();
            $pers_list = '';
            if($pers_tasks) {
                foreach($pers_tasks as $task) {
                    $query = new \yii\db\Query;
                    $query->select('*')
                        ->from('STIGIT.V_F_PERS')
                        ->where('TN = \'' . $task->TN .'\'');
                    $command = $query->createCommand();
                    $data = $command->queryOne();
                    $pers_list .= $data['FIO']."<br>";
                }
            } else {
                $pers_list = self::_UNDEFINED;
            }
            if(!empty($issue->SOURCENUM)) {
                $sourcenum = $issue->SOURCENUM;
            } else {
                $sourcenum = self::_UNDEFINED;
            }
            if(!empty($issue->ADDITIONAL_TEXT)) {
                $additional_text = $issue->ADDITIONAL_TEXT;
            } else {
                $additional_text = self::_UNDEFINED;
            }
            if(!empty($issue->REPORT_TEXT)) {
                $report_text = $issue->REPORT_TEXT;
            } else {
                $report_text = self::_UNDEFINED;
            }

            $task_date = \app\models\TaskDates::find()->where(['TASK_ID' => $issue_id, 'DEL_TRACT_ID' => null])->one();
            if($task_date) {
                $sektor_date = $task_date->TASK_TYPE_DATE;
            } else {
                $sektor_date = self::_UNDEFINED;
            }

            $task_date_first_time = \app\models\TaskDates::find()->where(['DATE_TYPE_ID' => '1', 'TASK_ID' => $issue_id])->one();
            if($task_date_first_time) {
                $first_date = $task_date_first_time->TASK_TYPE_DATE;
            } else {
                $first_date = self::_UNDEFINED;
            }

            $task_date_closed = \app\models\TaskDates::find()->where(['DATE_TYPE_ID' => '4', 'TASK_ID' => $issue_id])->one();
            if($task_date_closed) {
                $closed_date = $task_date_closed->TASK_TYPE_DATE;
            } else {
                $closed_date = self::_UNDEFINED;
            }

            $transactions = \app\models\Transactions::findOne($issue->TRACT_ID);

            $task_docs = \app\models\TaskDocs::find()->where(['TASK_ID' => $issue_id, 'DEL_TRACT_ID' => null])->all();
            if($task_docs) {
                $task_docs_list = 'сформировать список';
            } else {
                $task_docs_list = self::_UNDEFINED;
            }

            $task_confirms = \app\models\TaskConfirms::find()->where(['TASK_ID' => $issue_id, 'DEL_TRACT_ID' => null])->all();
            if($task_confirms) {
                $task_confirms_list = 'сформировать список';
            } else {
                $task_confirms_list = self::_UNDEFINED;
            }

            $task_docs_recvrs = \app\models\TaskDocsRecvrs::find()->where(['TASK_ID' => $issue_id, 'DEL_TRACT_ID' => null])->all();
            if($task_docs_recvrs) {
                $task_docs_recvrs_list = 'сформировать список';
            } else {
                $task_docs_recvrs_list = self::_UNDEFINED;
            }

            $task_states = \app\models\TaskStates::find()->where(['TASK_ID' => $issue_id])->all();
            if($task_states) {
                $task_states_list = 'сформировать список';
            } else {
                $task_states_list = self::_UNDEFINED;
            }


            $result_table = '<table class="table table-bordered">';
            $result_table .= '
                            <tr>
                                <td class="issue-table-label">Подразделения</td>
                                <td>'.$podr_list.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Исполнитель</td>
                                <td>'.$pers_list.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Исходящий номер</td>
                                <td>'.$issue->TASK_NUMBER.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Входящий номер</td>
                                <td>'.$sourcenum.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Заказ (изделие)</td>
                                <td>'.$issue->ORDERNUM.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Заказ ПЭО</td>
                                <td>'.$issue->PEOORDERNUM.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Срок выполнения</td>
                                <td>'.\Yii::$app->formatter->asDate($issue->DEADLINE, 'php:d-m-Y').'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Содержание</td>
                                <td>'.$issue->TASK_TEXT.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Дополнительные указания</td>
                                <td>'.$additional_text.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Дата поступления в сектор</td>
                                <td>'.$sektor_date.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Дата поступления в группу</td>
                                <td>'.\Yii::$app->formatter->asDate($transactions->TRACT_DATETIME, 'php:d-m-Y').'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Дата поступления исполнителю</td>
                                <td>'.$first_date.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Дата завершения</td>
                                <td>'.$closed_date.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Выпущенная документация</td>
                                <td>'.$task_docs_list.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Отчет о работе</td>
                                <td>'.$report_text.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Согласовано с</td>
                                <td>'.$task_confirms_list.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Передано в</td>
                                <td>'.$task_docs_recvrs_list.'</td>  
                            </tr>
                            <tr>
                                <td class="issue-table-label">Состояние</td>
                                <td>'.$task_states_list.'</td>  
                            </tr>
            ';
            $result_table .= '</table>';

            $items = ['issue_id' => $issue_id, 'issue_designation' => $issue->TASK_NUMBER, 'result_table' => $result_table];
            return $items;
        }
    }


    public function actionUpdateissue($id) {

        /*
            @todo сделать проверку на доступ к заданию пользователя
        */

        $model = $this->findModel($id);
        $model->DEADLINE = \Yii::$app->formatter->asDate($model->DEADLINE, 'php:d-m-Y');
        $podr_tasks = \app\models\PodrTasks::findAll(['TASK_ID' => $model->ID]);
        $pers_tasks = \app\models\PersTasks::findAll(['TASK_ID' => $model->ID]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            echo '<pre>';
            print_r($model->agreed_podr_list); die();

            //after save generate script to confirm and close window
            //return $this->redirect(['view', 'id' => (string) $model->_id]);
        } elseif (Yii::$app->request->isAjax) {
            $this->_podr_data_array = $this->_getPodrData();
            $this->_createPodrTree(1, 0);
            $this->_createPodrTreeAgreed(1, 0);
            $this->_createPodrTreeTransmitted(1, 0);

            return $this->renderAjax('_formupdateissue', [
                'model' => $model,
                'not_ajax' => false,
                'podr_data' => $this->_multidemensional_podr,
                'podr_tasks' => $podr_tasks,
                'pers_tasks' => $pers_tasks,
                'agreed_podr_data' => $this->_multidemensional_podr_agreed,
                'transmitted_podr_data' => $this->_multidemensional_podr_transmitted,
            ]);
        } else {
            $this->layout = 'updateissue';
            $this->_podr_data_array = $this->_getPodrData();
            $this->_createPodrTree(1, 0);
            $this->_createPodrTreeAgreed(1, 0);
            $this->_createPodrTreeTransmitted(1, 0);

            return $this->render('_formupdateissue', [
                'model' => $model,
                'not_ajax' => true,
                'podr_data' => $this->_multidemensional_podr,
                'podr_tasks' => $podr_tasks,
                'pers_tasks' => $pers_tasks,
                'agreed_podr_data' => $this->_multidemensional_podr_agreed,
                'transmitted_podr_data' => $this->_multidemensional_podr_transmitted,
            ]);
        }
    }

    protected function findModel($id)
    {
        if (($model = \app\models\Tasks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
        Метод авторизации
    */
    public function actionLogin()
    {
        $this->layout = 'empty';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            //записываем в таблицу TRANSACTIONS данные
            $transactions = new Transactions;
            $transactions->TN = new \yii\db\Expression('TO_NUMBER('.\Yii::$app->user->id.')');
            $transactions->TRACT_DATETIME = new \yii\db\Expression('SYSDATE');
            $transactions->USER_IP = $_SERVER['REMOTE_ADDR'];
            $transactions->insert();

            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /*
        Метод logout
    */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }



    
}
