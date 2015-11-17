<?php

namespace app\models;

use Yii;

class TaskStates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.TASK_STATES';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TASK_ID', 'STATE_ID', 'TRACT_ID', 'IS_CURRENT'], 'required'],
            [['PERS_TASKS_ID'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'TASK_ID' => 'Идентификатор текущей задачи',
            'STATE_ID' => 'Идентификатор указанного состояния',
            'TRACT_ID' => 'Идентификатор текущей транзакции пользователя',
            'IS_CURRENT' => 'Текущее состояние'
        ];
    }

    public function getState_name_state_colour_without_text()
    {
        $state = \app\models\States::findOne($this->STATE_ID);
        return '<img height="16" src="/images/items_status/'.$state->STATE_COLOUR.'.png">';
    }
    
}
