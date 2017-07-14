<?php

namespace app\modules\home\servers\TTSservice;
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/14
 * Time: 上午10:18
 */




class TTSservice{

    private static $_instance;
    private $third;

    public $sendData;

    private function __construct()
    {
    }

    public static function init($className){

        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        if(class_exists($className)&& get_parent_class($className) == TTSAbstarct::class){
            self::$_instance->third = new $className();
        }else{
            self::$_instance= null;
        }
         return self::$_instance;
    }


    public function __get($key)
    {
       return  self::$_instance->third;
    }

    public function __set($key, $value)
    {
        self::$_instance->third->$key = $value;
    }



    /**
     * 发送消息
     */
    public function sendMessage(){
        /**
         *这里实现业务
         */
        self::$_instance->third->sendMessage();   //这里只负责发送 消息 一次

    }

    /**
     *消息的回调处理
     */
    public function event(){

        return self::$_instance->third->event();
    }





}