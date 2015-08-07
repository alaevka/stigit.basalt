<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

class SearchTasks extends Tasks
{
    public function rules()
    {
        return [
            [['DESIGNATION'], 'integer'],
            //[['title'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
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

        // $query->andFilterWhere([
        //     //'DESIGNATION' => $this->DESIGNATION,
        // ]);

        //$query->andFilterWhere(['like', 'title', $this->title]);
        

        return $dataProvider;
    }
}
