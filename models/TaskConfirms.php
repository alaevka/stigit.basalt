<?php

namespace app\models;

use Yii;

class TaskConfirms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.TASK_CONFIRMS';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TASK_ID', 'KODZIFR', 'TARGET_CONFIRM_DATE', 'CONFIRM_TRACT_ID', 'TRACT_ID', 'DEL_TRACT_ID'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'TASK_ID' => 'Идентификатор текущей задачи',
            'KODZIFR' => 'Идентификатор согласующего подразделения',
            'TARGET_CONFIRM_DATE' => 'undefined date field',
            'CONFIRM_TRACT_ID' => 'undefined field',
            'TRACT_ID' => 'Идентификатор текущей транзакции пользователя',
            'DEL_TRACT_ID' => 'Идентификатор текущей транзакции пользователя',
        ];
    }
    
}
