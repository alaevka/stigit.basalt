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
            [['SUBJECT_ID', 'SUBJECT_TYPE', 'ACTION_ID', 'TRACT_ID', 'PERM_LEVEL', 'PERM_TYPE'], 'required'],
            [['DEL_TRACT_ID'], 'safe'],
        ];
    }

    
}
