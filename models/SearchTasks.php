<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

class SearchTasks extends Tasks
{
    public $states;


    public function rules()
    {
        return [
            [['DESIGNATION', 'TASK_NUMBER', 'ORDERNUM', 'PEOORDERNUM', 'SOURCENUM', 'TASK_TEXT', 'states', 'podr_list', 'persons_list'], 'safe'],
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

    public function search($params)
    {
        $query = Tasks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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
        if(!empty($this->persons_list)) {
            $persons_list = array_map('trim', explode(',', $this->persons_list));
            $query->joinWith('perstasks'); 
            $query->andFilterWhere(['PERS_TASKS.TN' => $persons_list]);
        }


        // $query->andFilterWhere([
        //     //'DESIGNATION' => $this->DESIGNATION,
        // ]);
        $query->andFilterWhere(['like', 'SOURCENUM', $this->SOURCENUM]);

        $query->andFilterWhere(['like', 'TASK_TEXT', $this->TASK_TEXT]);
        $query->andFilterWhere(['like', 'PEOORDERNUM', $this->PEOORDERNUM]);
        $query->andFilterWhere(['like', 'ORDERNUM', $this->ORDERNUM]);
        $query->andFilterWhere(['like', 'TASK_NUMBER', $this->TASK_NUMBER]);
        $query->andFilterWhere(['like', 'LOWER(DESIGNATION)', mb_strtolower($this->DESIGNATION, 'UTF-8')]);
        

        return $dataProvider;
    }
}
