<?php

namespace app\models;

use Yii;

class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.TRANSACTIONS';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TN', 'TRACT_DATETIME', 'USER_IP'], 'required'],
            [['ID'], 'safe']
        ];
    }

    
}
