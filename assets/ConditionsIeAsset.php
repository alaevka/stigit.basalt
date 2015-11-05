<?php

namespace app\assets;

use yii\web\AssetBundle;

class ConditionsIeAsset extends AssetBundle
{
    public $cssOptions = ['condition' => 'lte IE 9'];
    public $css = [
        'ace/css/ace-part2.min.css'
    ];
    
    
}
