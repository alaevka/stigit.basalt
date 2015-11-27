<?php
/*
    Класс прав доступа и настройки состояний
*/
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class PermissionsController extends Controller
{

    /*
        Метод формирования доступа к страницам
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
        ];
    }

    /*
        Метод формирования страницы "Права доступа"
    */
    public function actionIndex()
    {
        $permissions_for_change_permissions = \app\models\Permissions::find()->where('(SUBJECT_TYPE = :subject_type and SUBJECT_ID = :user_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL = :perm_level and ACTION_ID = :action) or 
            (SUBJECT_TYPE = :subject_type_dolg and SUBJECT_ID = :dolg_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL = :perm_level and ACTION_ID = :action)', ['subject_type_dolg' => 1, 'dolg_id' =>  \Yii::$app->session->get('user.user_iddolg'), 'action' => 1, 'subject_type' => 2, 'user_id' => \Yii::$app->user->id, 'del_tract' => 0, 'perm_level' => 2])->one();
        /*
            Проверка на доступ пользователя к странице
        */
        if($permissions_for_change_permissions) {
            /*
                Формирование списка должностей
            */
            $query = new \yii\db\Query;
            $query->select('NAIMDOLG AS name, IDDOLG AS id')
                    ->from('STIGIT.V_F_SHRAS')
                    //->limit(20)
                    ->orderBy('NAIMDOLG asc');
            $command = $query->createCommand();
            $v_f_shras = $command->queryAll();
            
            /*
                Формирование списка пользователей
            */
            $query = new \yii\db\Query;
            $query->select('TN AS tn, FIO AS fio')
                    ->from('STIGIT.V_F_PERS')
                    //->limit(20)
                    ->orderBy('FIO asc');
            $command = $query->createCommand();
            $v_f_pers = $command->queryAll(); 

            /*
                Формирование списка доступных действий
            */
            $actions = \app\models\Actions::find()->orderBy('ACTION_DESC asc')->all();

            /*
                Формирование списка состояний
            */
            $states_list = \app\models\States::find()->all();

            /*
                Рендер страницы и передача сформированных переменных в нее
            */
            return $this->render('index', [
                'v_f_shras' => $v_f_shras,
                'v_f_pers' => $v_f_pers,
                'actions' => $actions,
                'states_list' => $states_list,
            ]);
        } else {
            /*
                Вызываем эксепшн в случае, если доступ к странце запрещен
            */
            throw new \yii\web\ForbiddenHttpException('У Вас нет доступа на "Права доступа".'); 
        }
    }

    /*
        Метод формирования страницы смены состояний
    */
    public function actionStates()
    {
        $permissions_for_states_change = \app\models\Permissions::find()->where('SUBJECT_TYPE = :subject_type and SUBJECT_ID = :user_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL != :perm_level and ACTION_ID = :action', ['action' => 2, 'subject_type' => 2, 'user_id' => \Yii::$app->user->id, 'del_tract' => 0, 'perm_level' => 0])->one();
        /*
            Проверка на доступ пользователя к странице
        */
        if($permissions_for_states_change) {
            /*
                Формирование списка состояний
            */
            $states_list = \app\models\States::find()->all();
            /*
                Рендер страницы и передача сформированных переменных в нее
            */
            return $this->render('states', [
                'states_list' => $states_list,
                'perm_level' => $permissions_for_states_change->PERM_LEVEL
            ]);
        } else {
            /*
                Вызываем эксепшн в случае, если доступ к странце запрещен
            */
            throw new \yii\web\ForbiddenHttpException('У Вас нет доступа на "Последовательность смены состояний".'); 
        }
    }
}