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
            [['TASK_ID', 'STATE_ID', 'TRACT_ID'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'TASK_ID' => 'Идентификатор текущей задачи',
            'STATE_ID' => 'Идентификатор указанного состояния',
            'TRACT_ID' => 'Идентификатор текущей транзакции пользователя',
        ];
    }
    
}
