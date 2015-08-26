<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'todc-bootstrap/css/bootstrap.min.css',
        'todc-bootstrap/css/todc-bootstrap.min.css',
        'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css',
        'css/jgrowl.css',
        'css/select2.css',
        'css/bootstrap-tokenfield.min.css',
        'css/jquery.tree.min.css',
        'css/site.css',
    ];
    public $js = [
        'http://code.jquery.com/ui/1.10.1/jquery-ui.js',
        'js/jgrowl.min.js',
        'js/bootstrap-tokenfield.min.js',
        'js/jquery.tree.min.js',
        'js/bootbox.min.js',
        'js/scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];


}
