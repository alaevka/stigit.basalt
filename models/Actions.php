<?php
/*
    Модель действий для назначения прав
*/
namespace app\models;

use Yii;

class Actions extends \yii\db\ActiveRecord
{
    /**
     * метод, возвращающий имя таблицы ACTIONS
     */
    public static function tableName()
    {
        return 'DEV03.ACTIONS';
    }

    /**
     * валидация данных
     */
    public function rules()
    {
        return [
            [['ACTION', 'ACTION_DESC'], 'required'],
        ];
    }

    
}
