<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2016/12/10
 * Time: 11:30
 */

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    Public $css = [
        'css/global/plugins/bootstrap-table/bootstrap-table.min.css'
    ];
    public $js = [
        'js/home/content.js',
        'js/global/plugins/bootstrap-table/bootstrap-table.min.js',
        'js/global/plugins/bootstrap-table/bootstrap-table-mobile.min.js',
        'js/global/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js',
        'js/global/plugins/bootstrap-table/bootstrap-table.min.js',
    ];

    public $depends = [
        'app\assets\GlobalAsset',
    ];
}