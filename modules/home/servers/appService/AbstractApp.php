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
     * @return mixed
     * app的回调
     * 解析成标准的回调数据
     * 提供给业务处理层 作为处理依据
     */
    abstract  function Event();

    /**
     * @return mixed
     * 像app发送消息
     */
    abstract  function Send();

    /**
     * @return mixed
     * 应答app的回调
     */
    abstract  function Anwser();






}