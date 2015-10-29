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
        'css/jquery-ui.css',
        'css/jgrowl.css',
        'css/select2.css',
        'css/bootstrap-tokenfield.min.css',
        'css/jquery.tree.min.css',
        'css/jstree.min.css',
        'css/fileinput.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery-ui.js',
        'js/jquery-sortable.js',
        'js/jgrowl.min.js',
        'js/bootstrap-tokenfield.min.js',
        'js/jquery.tree.min.js',
        'js/jstree.min.js',
        'js/bootbox.min.js',
        'js/fileinput.min.js',
        'js/fileinput_locale_ru.js',
        'js/scripts.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

}
