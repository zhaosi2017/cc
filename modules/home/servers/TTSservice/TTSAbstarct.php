<?php
namespace app\modules\home\servers\TTSservice;
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/14
 * Time: 上午10:18
 */

abstract  class TTSAbstarct{

    public $voice  ;          //语音播报声音
    public $Language;         //使用的语言
    public $loop;             //重复播报次数

    public $messageText;      //消息内容
    public $messageText_more; //第二次发送的消息 当有更多的联系电话时 消息可以设置
    public $messageType;      //消息类型  短信 语音播报
    public $messageId;        //发送的消息的id
    public $messageStatus;    //发送状态
    public $messageAnwser;    //消息应答




    public $duration;         //通话时长
    public $to;
    public $from;

    public $error;




    /**
     * @return bool
     *
     */
    abstract public function sendMessage();         //发送消息

    /**
     * @param $event_data
     * @return mixed
     */
    abstract public function event($event_data);         //处理回调数据



    public function setError($error){
            $this->error = $error;
    }



}