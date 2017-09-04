<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 上午11:25
 * 这里只定义和app交互的接口和基本数据 不处理业务
 */
namespace app\modules\home\servers\appService;

abstract class AbstractApp{
    /**
     * @var AppUserInfo
     * 会话发起用户
     */
    public $from_user;

    /**
     * 接受会话的用户
     * @var AppUserInfo
     */
    public $to_user;

    /**
     * @var string
     * app用户使用的语言
     */
    public $language;

    /**
     * @var AppCallBack
     * 回调的数据
     */
    public $callbcakData;
    /**
     * @var Send
     * 发送出去的数据
     */
    public $send_data;


    public function __construct()
    {
        $this->from_user    = new AppUserInfo();
        $this->to_user      = new AppUserInfo();
        $this->callbcakData = new AppCallBack();
        $this->send_data    = new Send();
    }

    /**
     * @return mixed
     * app的回调
     * 解析成标准的回调数据
     * 其中装填了用户的模型数据
     * 提供给业务处理层 作为处理依据
     */
    public abstract  function Event($data);

    /**
     * @param $text stting 文本内容
     * @param $type int    消息类型
     * @return mixed
     * 像app发送消息
     */
    public abstract  function Send();

    /**
     * @return mixed
     * 应答app的回调
     */
    public abstract  function Anwser();



}


