<?php

namespace app\models;

use Yii;

class Tasks extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE = 'update';

    public $podr_list;
    public $persons_list;
    public $task_type_date_3;
    public $transactions_tract_datetime;
    public $task_type_date_1;
    public $task_type_date_4;
    public $documentation;
    public $agreed_podr_list;
    public $transmitted_podr_list;
    public $state;

    public $hidden_ordernum;
    public $hidden_peoordernum;

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
            [['TASK_NUMBER'], 'integer'],
            [['TASK_NUMBER'], 'unique'],
            [['SOURCENUM', 'state', 'podr_list'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['SOURCENUM'], 'string', 'max' => 25],
            [['DOCUMENTID', 'DEL_TRACT_ID', 'ADDITIONAL_TEXT', 'SOURCENUM', 'REPORT_TEXT', 'podr_list', 'persons_list', 'task_type_date_3', 'task_type_date_1', 'task_type_date_4', 'documentation', 'agreed_podr_list', 'transmitted_podr_list', 'state', 'hidden_ordernum', 'hidden_peoordernum'], 'safe'],
        ];
    }


    public function getTaskstates()
    {
        return $this->hasMany(\app\models\TaskStates::className(), ['TASK_ID' => 'ID'])->where(['IS_CURRENT' => 1]);
    }

    public function getPodrtasks()
    {
        return $this->hasMany(\app\models\PodrTasks::className(), ['TASK_ID' => 'ID'])->where('PODR_TASKS.DEL_TRACT_ID = :del_tract_id', ['del_tract_id'=>0]);
    }

    public function getTaskconfirms()
    {
        return $this->hasMany(\app\models\TaskConfirms::className(), ['TASK_ID' => 'ID'])->where('TASK_CONFIRMS.DEL_TRACT_ID = :del_tract_id', ['del_tract_id'=>0]);
    }

    public function getPerstasks()
    {
        return $this->hasMany(\app\models\PersTasks::className(), ['TASK_ID' => 'ID'])->where('PERS_TASKS.DEL_TRACT_ID = :del_tract_id', ['del_tract_id'=>0]);
    }

    public function getTaskdocs()
    {
        return $this->hasMany(\app\models\TaskDocs::className(), ['TASK_ID' => 'ID'])->where('TASK_DOCS.DEL_TRACT_ID = :del_tract_id', ['del_tract_id'=>0]);
    }

    public function getDatetype3()
    {
        return $this->hasOne(\app\models\TaskDates::className(), ['TASK_ID' => 'ID'])->where('TASK_DATES.DEL_TRACT_ID = :del_tract_id and TASK_DATES.DATE_TYPE_ID = :date_type_id', ['del_tract_id'=>0, 'date_type_id'=>3]);
    }

    public function getDatetype2()
    {
        return $this->hasOne(\app\models\TaskDates::className(), ['TASK_ID' => 'ID'])->where('TASK_DATES.DEL_TRACT_ID = :del_tract_id and TASK_DATES.DATE_TYPE_ID = :date_type_id', ['del_tract_id'=>0, 'date_type_id'=>2]);
    }

    public function getDatetype1()
    {
        return $this->hasOne(\app\models\TaskDates::className(), ['TASK_ID' => 'ID'])->where('TASK_DATES.DEL_TRACT_ID = :del_tract_id and TASK_DATES.DATE_TYPE_ID = :date_type_id', ['del_tract_id'=>0, 'date_type_id'=>1]);
    }

    public function getDatetype4()
    {
        return $this->hasOne(\app\models\TaskDates::className(), ['TASK_ID' => 'ID'])->where('TASK_DATES.DEL_TRACT_ID = :del_tract_id and TASK_DATES.DATE_TYPE_ID = :date_type_id', ['del_tract_id'=>0, 'date_type_id'=>4]);
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
            'task_type_date_3' => 'Дата поступления в сектор',
            'transactions_tract_datetime' => 'Дата поступления в группу',
            'task_type_date_1' => 'Дата поступления исполнителю',
            'task_type_date_4' => 'Дата завершения',
            'documentation' => 'Выпущенная документация',
            'agreed_podr_list' => 'Согласовано с',
            'transmitted_podr_list' => 'Передано в',
            'state' => 'Состояние',
        ];
    }

    public function _getLastTaskStatus($id) {
        $task_state = \app\models\TaskStates::find()->where(['TASK_ID' => $id])->orderBy('ID DESC')->LIMIT(1)->one();
        if($task_state) {
            $this_task_state = \app\models\States::findOne($task_state->STATE_ID);
            return $this_task_state->getState_name_state_colour_without_text();
        } else {
            return '';
        }
    }
    
}
