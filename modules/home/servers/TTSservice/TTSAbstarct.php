<?php
namespace app\modules\home\servers\TTSservice;
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/14
 * Time: 上午10:18
 */

abstract  class TTSAbstarct{

    public $voice ;   //语音播报声音
    public $Language; //使用的语言

    public $messageText; //消息内容
    public $messageType; //消息类型  短信 语音播报

    public $messageStatus; //发送状态

    public $messageId;    //发送的消息的id


    abstract public function sendMessage();   //发送消息
    abstract public function event();         //处理回调数据


}