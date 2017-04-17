<?php

namespace app\assets;

use yii\web\AssetBundle;

class PublicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    /*public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome.css',
        'css/animate.css',
        'css/style.css',
    ];*/
    public $js = [
        'js/public/jquery.metisMenu.js',
        'js/public/jquery.slimscroll.min.js',
        'js/public/pace.min.js',
        'js/home/hplus.js?v=4.1.0',
        'js/home/contabs.js',
    ];
    public $depends = [
        'app\assets\GlobalAsset',
    ];
}
