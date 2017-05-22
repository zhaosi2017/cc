<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2016/12/10
 * Time: 11:30
 */

namespace app\assets;

use yii\web\AssetBundle;

class FormAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/global/plugins/iCheck/icheck.min.js',
        'js/global/plugins/validate/jquery.validate.min.js',
        'js/global/plugins/validate/messages_zh.min.js',
        'js/home/content.js',
    ];
    public $jsOptions =['
        $(\'.i-checks\').iCheck({
                    checkboxClass: \'icheckbox_square-green\',
                    radioClass: \'iradio_square-green\',
                });
        ',3];
    public $depends = [
        'app\assets\GlobalAsset',
    ];
}