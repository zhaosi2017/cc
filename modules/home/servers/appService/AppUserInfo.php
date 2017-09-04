<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 上午11:27
 */
namespace app\modules\home\servers\appService;

use app\modules\home\models\User;

class AppUserInfo{
    /**
     * @var int
     * app的用户id
     */
    public $app_uid;
    /**
     * @var string
     */
    public $app_first_name;

    /**
     * @var string
     */
    public $app_last_name;
    /**
     * @var string
     */
    public $app_name;
    /**
     * @var string
     * 电话号码
     */
    public $app_phone_number;

    /**
     * @var User
     * 该用户对应的本地用户基本信息
     */
    public $model_user;

    /**
     * @return string
     * 获取完整的用户名
     */
    public function getName(){
        if(empty($this->app_name)){
            $this->app_name = $this->app_last_name.$this->app_first_name;
        }
        return $this->app_name;
    }


}