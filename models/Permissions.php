<?php

namespace app\models;

use Yii;

class Permissions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DEV03.PERMISSIONS';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SUBJECT_ID', 'SUBJECT_TYPE', 'ACTION_ID', 'TRACT_ID', 'PERM_TYPE'], 'required'],
            [['PERM_LEVEL', 'DEL_TRACT_ID'], 'safe'],
        ];
    }

    public function getPermactiontype()
    {
        return $this->hasOne(\app\models\Actions::className(), ['ID' => 'ACTION_ID']);
    }

    public function getPermstatestype()
    {
        return $this->hasOne(\app\models\States::className(), ['ID' => 'ACTION_ID']);
    }

    
}
