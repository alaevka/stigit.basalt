<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class IssueForm extends Model
{
    public $designation;
    public $task_number;
    public $podr_list;
    public $persons_list;
    public $ordernum;
    public $peoordernum;
    public $message;
    public $date;
    public $documentid;
    


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['designation', 'task_number', 'podr_list', 'ordernum', 'peoordernum', 'message', 'date'], 'required'],
            [['task_number'], 'integer'],
            [['task_number'], 'validateUniqueTaskNumber'],
            [['persons_list', 'documentid'], 'safe']
        ];
    }

    public function validateUniqueTaskNumber()
    {
        $tasks = Tasks::find()->where(['TASK_NUMBER' => $this->task_number])->one();
        if($tasks) {
            $this->addError('task_number', 'Исходящий номер уже зарегистрирован в системе. Укажите другой.');
        }
    }

    public function attributeLabels()
    {
        return [
            'designation' => 'Основание',
            'task_number' => 'Исходящий номер',
            'podr_list' => 'Подразделения',
            'persons_list' => 'Исполнители',
            'ordernum' => 'Заказ (изделие)',
            'peoordernum' => 'Заказ ПЭО',
            'message' => 'Содержание задания',
            'date' => 'Срок выполнения',
        ];
    }

}