<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 上午11:39
 * 这个文件 处理本地业务
 */
namespace app\modules\home\servers\appService;

use app\modules\home\models\User;
use app\modules\home\models\UserBindApp;

class AppService{
    /**
     * @param $app_userid appid
     * @param $app_type   app类型
     * @return bool|static
     */
    public static function getUserByApp($app_userid , $app_type){
        $app_name = UserBindApp::$APP_TYPE_MAPS[$app_type];
        if(empty($app_name)){
            return false;
        }
        $c_user_id =  $app_name.'_user_id';
        $c_number = $app_name.'_number';
        $c_country_code = $app_name.'_country_code';
        $c_name   = $app_name.'_name';

        $user = User::findOne([$c_user_id=>$app_userid]);
        if(empty($user)){
            $app = UserBindApp::findOne(['app_userid'=>$app_userid , 'type'=>$app_type]);
            if(empty($app)){
                return false;
            }
            $user = User::findOne(['id'=>$app->user_id]);
            if(empty($user)){
                $app->delete();
                return false;
            }
            $user->$c_user_id = $app->app_userid;
            $user->$c_number  = $app->app_number; //含国码
            $user->$c_country_code  = '';
            $user->$c_name  = $app->app_name;
            return $user;
        }
    }










}