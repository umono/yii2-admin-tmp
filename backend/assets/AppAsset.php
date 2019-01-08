<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
//        'https://fonts.googleapis.com/css?family=Nunito+Sans:400,600',
        // 'css/bootstrap.min.css',
        'layui/css/layui.css',
//        'css/admin.css',
    ];
    public $js = [
        'layui/layui.js',
        'js/style.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
