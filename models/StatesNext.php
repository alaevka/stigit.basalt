<?php

namespace app\models;

use Yii;

class StatesNext extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.STATES_NEXT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['STATE_ID', 'NEXT_STATE_ID', 'TRACT_ID'], 'required'],
            [['DEL_TRACT_ID'], 'safe'],
        ];
    }

    
}
