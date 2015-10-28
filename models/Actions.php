<?php

namespace app\models;

use Yii;

class Actions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.ACTIONS';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ACTION', 'ACTION_DESC'], 'required'],
        ];
    }

    
}
