<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/10/28
 * Time: 下午2:19
 */


namespace app\modules\home\models;

use Yii;
use app\models\CActiveRecord;



class UserBindApp extends CActiveRecord{

    const APP_TYPE_POTATO   = 0;
    const APP_TYPE_TELEGRAM = 1;

    static public $APP_TYPE_MAPS = [
        self::APP_TYPE_POTATO=>'potato',
        self::APP_TYPE_TELEGRAM=>'telegram'
    ];



    public static function tableName()
    {
        return 'user_bindapp';
    }
















}