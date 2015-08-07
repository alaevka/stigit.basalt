<?php

namespace app\models;

use Yii;

class Tasks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.TASKS';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DESIGNATION', 'TASK_NUMBER', 'ORDERNUM', 'PEOORDERNUM', 'TASK_TEXT', 'DEADLINE', 'TRACT_ID'], 'required'],
            [['DOCUMENTID', 'DEL_TRACT_ID'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'DESIGNATION' => 'Основание',
            
        ];
    }
    
}
