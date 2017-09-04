<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 上午11:27
 */
namespace app\modules\home\servers\appService;

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
     * 电话号码
     */
    public $app_phone_number;

    /**
     * @return string
     * 获取完整的用户名
     */
    public function getName(){

        return $this->app_last_name.$this->app_first_name;
    }


}