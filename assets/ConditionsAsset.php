<?php

namespace app\backend\assets;

use yii\web\AssetBundle;

class ConditionsAsset extends AssetBundle
{
    public $cssOptions = ['condition' => 'lte IE 9'];
    public $css = [
        'ace/css/ace-part2.min.css'
    ];
    
    
}
