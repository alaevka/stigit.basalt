<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class PermissionsController extends Controller
{

    /*
        Метод формирования доступа к страницам
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['logout', 'index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $query = new \yii\db\Query;
        $query->select('NAIMDOLG AS name, IDDOLG AS id')
                ->from('STIGIT.V_F_SHRAS')
                //->limit(20)
                ->orderBy('NAIMDOLG asc');
        $command = $query->createCommand();
        $v_f_shras = $command->queryAll();
 
        $query = new \yii\db\Query;
        $query->select('TN AS tn, FIO AS fio')
                ->from('STIGIT.V_F_PERS')
                //->limit(20)
                ->orderBy('FIO asc');
        $command = $query->createCommand();
        $v_f_pers = $command->queryAll(); 

        $actions = \app\models\Actions::find()->orderBy('ACTION_DESC asc')->all();

        $states_list = \app\models\States::find()->all();

        return $this->render('index', [
            'v_f_shras' => $v_f_shras,
            'v_f_pers' => $v_f_pers,
            'actions' => $actions,
            'states_list' => $states_list,
        ]);
    }
}