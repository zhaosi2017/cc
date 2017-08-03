<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 上午11:44
 * 处理回调有关的数据
 */
namespace app\modules\home\servers\appService;

use app\modules\home\models\CallRecord;

class AppCallBack{

    /**
     * @var string
     * 回调的原始json数据
     */
    public $json_data;

    /**
     * @var ArrayAccessible
     * 回调的解析数据
     */
    public $array_data;
    /**
     * @var int
     * 回调的类型
     */
    public $CallBack_type;
    /**
     * 消息之间按位占位，避免消息重复判定
     */
    CONST CALLBACK_TYPE_FIRST   = 1;   //发送 /start 命令
    CONST CALLBACK_TYPE_MYSELF  = 2;   //分享自己的名片
    CONST CALLBACK_TYPE_CONTACT = 4;   //分享别人的名片
    CONST CALLBACK_TYPE_MAP     = 8;   //发送了地图的命令
    CONST CALLBACK_TYPE_BUTTON  = 16;  //触发了回调的按钮
    CONST CALLBACK_TYPE_REPLAY  = 32;  //回复了消息
    /**
     * @var string
     * 回调的文本
     */
    public $text;
    /**
     * @var int
     * 回调的会话 app id
     */
    public $chat_id;
    /**
     * @var int
     * 发起者的app id
     */
    public $from_id;
    /**
     * @var int
     * 被操作者的app id
     */
    public $to_id;
    /**
     * @var CallBackData
     * 预制的回调数据
     */
    public $callback_data;


    public function __construct()
    {
        $this->callback_data = new CallBackData();
    }

}

/**
 * Class CallBackData
 * 这个类定义 我们的回调预制数据
 */
class CallBackData{

    /**
     * @var Array
     * 解析之后的回调数据数组
     */
    private $array_data;

    /**
     * @var string
     * 解析之前的回调数据
     */
    private $string_data;




    public function setArrayData(Array $Array){
        $this->array_data =  $Array;
        $this->string_data = explode('-' ,$this->array_data );
    }

    public function getArrayData(){
        return  $this->array_data;
    }


    public function setStringData( $str){
        $this->string_data = $str;
        $this->array_data = implode('-' , $this->string_data);
    }

    public function getStringData(){
        return  $this->string_data;
    }

}