<?php

namespace app\models;

use Yii;

class States extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.STATES';
    }

    public function getState_name_state_colour()
    {
        return '<img height="16" src="/images/items_status/'.$this->STATE_COLOUR.'.png"> '.$this->STATE_NAME;
    }

    public function getState_name_state_colour_without_text()
    {
        return '<img height="16" src="/images/items_status/'.$this->STATE_COLOUR.'.png">';
    }

    public function getState_name_state_colour_css()
    {
        return '/images/items_status/'.$this->STATE_COLOUR.'.png';
    }

    public function getStatesnext()
    {
        return $this->hasMany(\app\models\StatesNext::className(), ['STATE_ID' => 'ID'])->where(['DEL_TRACT_ID' => 0]);
    }
    
}
