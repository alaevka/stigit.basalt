<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

class SearchTasks extends Tasks
{
    public $states;
    public $deadline_from;
    public $deadline_to;
    public $task_type_date_3_from;
    public $task_type_date_3_to;
    public $task_type_date_1_from;
    public $task_type_date_1_to;
    public $task_type_date_4_from;
    public $task_type_date_4_to;
    public $task_type_date_2_from;
    public $task_type_date_2_to;
    private $dateFormat = 'YYYY-MM-DD hh24:mi:ss';


    public function rules()
    {
        return [
            [['DESIGNATION', 'TASK_NUMBER', 'ORDERNUM', 'PEOORDERNUM', 'SOURCENUM', 'DEADLINE', 'TASK_TEXT', 'states', 'podr_list', 'persons_list', 'deadline_from', 'deadline_to',
            'task_type_date_3_from', 'task_type_date_3_to', 'task_type_date_1_from', 'task_type_date_1_to', 'task_type_date_4_from', 'task_type_date_4_to', 
            'task_type_date_2_from', 'task_type_date_2_to', 'documentation', 'agreed_podr_list'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function getSelectedTasksStatesNames()
    {
        $selected_states = \app\models\States::find()->where(['ID' => $this->states])->all();
        if($selected_states) {
            $selected_states_string = 'выбрано: ';
            foreach($selected_states as $state) {
                $selected_states_string .= $state->STATE_NAME.' ';
            }  
            return $selected_states_string;
        }
        
    }

    public function attributeLabels()
    {
        return [
            'deadline_from' => 'От',
            'deadline_to' => 'До',
            'task_type_date_3_from' => 'От',
            'task_type_date_3_to' => 'До',
            'task_type_date_1_from' => 'От',
            'task_type_date_1_to' => 'До',
            'task_type_date_4_from' => 'От',
            'task_type_date_4_to' => 'До',
            'task_type_date_2_from' => 'От',
            'task_type_date_2_to' => 'До',
        ];
    }

    public function search($params)
    {
        $query = Tasks::find();//->joinWith('datetype2');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['ID'=> SORT_DESC]]
        ]);

        //$dataProvider->sort->defaultOrder = ['TASK_DATES.TASK_TYPE_DATE' => SORT_DESC];


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        if(!empty($this->states)) {
            $query->joinWith('taskstates'); 
            $query->andFilterWhere(['TASK_STATES.STATE_ID' => $this->states]);
        }
        if(!empty($this->podr_list)) {
            $podr_list = array_map('trim', explode(',', $this->podr_list));
            $query->joinWith('podrtasks'); 
            $query->andFilterWhere(['PODR_TASKS.KODZIFR' => $podr_list]);
        }
        if(!empty($this->agreed_podr_list)) {
            $agreed_podr_list = array_map('trim', explode(',', $this->agreed_podr_list));
            $query->joinWith('taskconfirms'); 
            $query->andFilterWhere(['TASK_CONFIRMS.KODZIFR' => $agreed_podr_list]);
        }
        if(!empty($this->persons_list)) {
            $persons_list = array_map('trim', explode(',', $this->persons_list));
            $query->joinWith('perstasks'); 
            $query->andFilterWhere(['PERS_TASKS.TN' => $persons_list]);
        }
        if(!empty($this->documentation)) {
            $query->joinWith('taskdocs'); 
            $query->andFilterWhere(['TASK_DOCS.DOC_CODE' => $this->documentation]);
        }
        if($this->deadline_from != '' && $this->deadline_to != '') {
            $deadline_from = explode('-', $this->deadline_from);
            $deadline_from_formatted = $deadline_from[2].'-'.$deadline_from[1].'-'.$deadline_from[0];
            $deadline_to = explode('-', $this->deadline_to);
            $deadline_to_formatted = $deadline_to[2].'-'.$deadline_to[1].'-'.$deadline_to[0];
            $query->andFilterWhere(['>=', 'DEADLINE', new \yii\db\Expression("to_date('" . $deadline_from_formatted . "','{$this->dateFormat}')")])
                    ->andFilterWhere(['<=', 'DEADLINE', new \yii\db\Expression("to_date('" . $deadline_to_formatted . "','{$this->dateFormat}')")]);
        } else if($this->deadline_from != '' && $this->deadline_to == '') {
            $deadline_from = explode('-', $this->deadline_from);
            $deadline_from_formatted = $deadline_from[2].'-'.$deadline_from[1].'-'.$deadline_from[0];
            $query->andFilterWhere(['>=', 'DEADLINE', new \yii\db\Expression("to_date('" . $deadline_from_formatted . "','{$this->dateFormat}')")]);
        } else if($this->deadline_from == '' && $this->deadline_to != '') {
            $deadline_to = explode('-', $this->deadline_to);
            $deadline_to_formatted = $deadline_to[2].'-'.$deadline_to[1].'-'.$deadline_to[0];
            $query->andFilterWhere(['<=', 'DEADLINE', new \yii\db\Expression("to_date('" . $deadline_to_formatted . "','{$this->dateFormat}')")]);
        }
        if($this->task_type_date_3_from != '' && $this->task_type_date_3_to != '') {
            $query->joinWith('datetype3'); 
            $task_type_date_3_from = explode('-', $this->task_type_date_3_from);
            $task_type_date_3_from_formatted = $task_type_date_3_from[2].'-'.$task_type_date_3_from[1].'-'.$task_type_date_3_from[0];
            $task_type_date_3_to = explode('-', $this->task_type_date_3_to);
            $task_type_date_3_to_formatted = $task_type_date_3_to[2].'-'.$task_type_date_3_to[1].'-'.$task_type_date_3_to[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_3_from_formatted . "','{$this->dateFormat}')")])
                    ->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_3_to_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_3_from != '' && $this->task_type_date_3_to == '') {
            $query->joinWith('datetype3'); 
            $task_type_date_3_from = explode('-', $this->task_type_date_3_from);
            $task_type_date_3_from_formatted = $task_type_date_3_from[2].'-'.$task_type_date_3_from[1].'-'.$task_type_date_3_from[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_3_from_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_3_from == '' && $this->task_type_date_3_to != '') {
            $query->joinWith('datetype3'); 
            $task_type_date_3_to = explode('-', $this->task_type_date_3_to);
            $task_type_date_3_to_formatted = $task_type_date_3_to[2].'-'.$task_type_date_3_to[1].'-'.$task_type_date_3_to[0];
            $query->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_3_to_formatted . "','{$this->dateFormat}')")]);
        }
        if($this->task_type_date_1_from != '' && $this->task_type_date_1_to != '') {
            $query->joinWith('datetype1'); 
            $task_type_date_1_from = explode('-', $this->task_type_date_1_from);
            $task_type_date_1_from_formatted = $task_type_date_1_from[2].'-'.$task_type_date_1_from[1].'-'.$task_type_date_1_from[0];
            $task_type_date_1_to = explode('-', $this->task_type_date_1_to);
            $task_type_date_1_to_formatted = $task_type_date_1_to[2].'-'.$task_type_date_1_to[1].'-'.$task_type_date_1_to[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_1_from_formatted . "','{$this->dateFormat}')")])
                    ->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_1_to_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_1_from != '' && $this->task_type_date_1_to == '') {
            $query->joinWith('datetype1'); 
            $task_type_date_1_from = explode('-', $this->task_type_date_1_from);
            $task_type_date_1_from_formatted = $task_type_date_1_from[2].'-'.$task_type_date_1_from[1].'-'.$task_type_date_1_from[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_1_from_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_1_from == '' && $this->task_type_date_1_to != '') {
            $query->joinWith('datetype1'); 
            $task_type_date_1_to = explode('-', $this->task_type_date_1_to);
            $task_type_date_1_to_formatted = $task_type_date_1_to[2].'-'.$task_type_date_1_to[1].'-'.$task_type_date_1_to[0];
            $query->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_1_to_formatted . "','{$this->dateFormat}')")]);
        }
        if($this->task_type_date_4_from != '' && $this->task_type_date_4_to != '') {
            $query->joinWith('datetype4'); 
            $task_type_date_4_from = explode('-', $this->task_type_date_4_from);
            $task_type_date_4_from_formatted = $task_type_date_4_from[2].'-'.$task_type_date_4_from[1].'-'.$task_type_date_4_from[0];
            $task_type_date_4_to = explode('-', $this->task_type_date_4_to);
            $task_type_date_4_to_formatted = $task_type_date_4_to[2].'-'.$task_type_date_4_to[1].'-'.$task_type_date_4_to[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_4_from_formatted . "','{$this->dateFormat}')")])
                    ->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_4_to_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_4_from != '' && $this->task_type_date_4_to == '') {
            $query->joinWith('datetype4'); 
            $task_type_date_4_from = explode('-', $this->task_type_date_4_from);
            $task_type_date_4_from_formatted = $task_type_date_4_from[2].'-'.$task_type_date_4_from[1].'-'.$task_type_date_4_from[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_4_from_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_4_from == '' && $this->task_type_date_4_to != '') {
            $query->joinWith('datetype4'); 
            $task_type_date_4_to = explode('-', $this->task_type_date_4_to);
            $task_type_date_4_to_formatted = $task_type_date_4_to[2].'-'.$task_type_date_4_to[1].'-'.$task_type_date_4_to[0];
            $query->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_4_to_formatted . "','{$this->dateFormat}')")]);
        }
        if($this->task_type_date_2_from != '' && $this->task_type_date_2_to != '') {
            $query->joinWith('datetype2'); 
            $task_type_date_2_from = explode('-', $this->task_type_date_2_from);
            $task_type_date_2_from_formatted = $task_type_date_2_from[2].'-'.$task_type_date_2_from[1].'-'.$task_type_date_2_from[0];
            $task_type_date_2_to = explode('-', $this->task_type_date_2_to);
            $task_type_date_2_to_formatted = $task_type_date_2_to[2].'-'.$task_type_date_2_to[1].'-'.$task_type_date_2_to[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_2_from_formatted . "','{$this->dateFormat}')")])
                    ->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_2_to_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_2_from != '' && $this->task_type_date_2_to == '') {
            $query->joinWith('datetype2'); 
            $task_type_date_2_from = explode('-', $this->task_type_date_2_from);
            $task_type_date_2_from_formatted = $task_type_date_2_from[2].'-'.$task_type_date_2_from[1].'-'.$task_type_date_2_from[0];
            $query->andFilterWhere(['>=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_2_from_formatted . "','{$this->dateFormat}')")]);
        } else if($this->task_type_date_2_from == '' && $this->task_type_date_2_to != '') {
            $query->joinWith('datetype2'); 
            $task_type_date_2_to = explode('-', $this->task_type_date_2_to);
            $task_type_date_2_to_formatted = $task_type_date_2_to[2].'-'.$task_type_date_2_to[1].'-'.$task_type_date_2_to[0];
            $query->andFilterWhere(['<=', 'TASK_DATES.TASK_TYPE_DATE', new \yii\db\Expression("to_date('" . $task_type_date_2_to_formatted . "','{$this->dateFormat}')")]);
        }
        $query->andFilterWhere(['like', 'SOURCENUM', $this->SOURCENUM]);
        $query->andFilterWhere(['like', 'TASK_TEXT', $this->TASK_TEXT]);
        $query->andFilterWhere(['or like', 'PEOORDERNUM', $this->PEOORDERNUM]);
        $query->andFilterWhere(['or like', 'ORDERNUM', $this->ORDERNUM]);
        $query->andFilterWhere(['like', 'TASK_NUMBER', $this->TASK_NUMBER]);
        $query->andFilterWhere(['like', 'LOWER(DESIGNATION)', mb_strtolower($this->DESIGNATION, 'UTF-8')]);
        return $dataProvider;
    }
}
