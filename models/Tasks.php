<?php

namespace app\models;

use Yii;

class Tasks extends \yii\db\ActiveRecord
{

    public $podr_list;
    public $persons_list;

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
            [['TASK_NUMBER'], 'unique'],
            [['SOURCENUM'], 'string', 'max' => 25],
            [['DOCUMENTID', 'DEL_TRACT_ID', 'ADDITIONAL_TEXT', 'SOURCENUM', 'REPORT_TEXT', 'podr_list', 'persons_list'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'DESIGNATION' => 'Основание',
            'TASK_NUMBER' => 'Исходящий номер',
            'ORDERNUM' => 'Заказ (изделие)',
            'PEOORDERNUM' => 'Заказ ПЭО',
            'TASK_TEXT' => 'Содержание задания',
            'DEADLINE' => 'Срок выполнения',
            'TRACT_ID' => 'id транзакции',
            'DOCUMENTID' => 'id основания',
            'DEL_TRACT_ID' => 'if deleted',
            'SOURCENUM' => 'Входящий номер',
            'ADDITIONAL_TEXT' => 'Дополнительные указания',
            'REPORT_TEXT' => 'Отчет о работе',

            'podr_list' => 'Подразделения',
            'persons_list' => 'Исполнитель',
        ];
    }
    
}
