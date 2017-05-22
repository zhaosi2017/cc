<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2016/12/10
 * Time: 11:30
 */

namespace app\assets;

use yii\web\AssetBundle;

class JqueryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/global/jquery.min.js?v=2.1.4',
    ];
}