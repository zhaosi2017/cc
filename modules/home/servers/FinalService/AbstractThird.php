<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/11
 * Time: 下午2:26
 * 三方接口规范
 * 规范之下的接口 只做数据处理，不做业务处理
 */

namespace app\modules\home\servers\FinalService;

use app\modules\home\models\FinalMerchantInfo;

abstract  class AbstractThird{
    /**
     * @var  FinalMerchantInfo
     * 包含了具体的交易平台的信息
     */
    public $Merchant;


    /**
     * @var string 请求类型
     * 值由具体的实现子类 赋予 供业务层调用
     * post ，get
     */
    public $request_type;

    /**
     * @var array 请求的数据
     */
    public $request_data = [
        'order_id'=>'',        //订单id
        'order_amount'=>''     //订单金额
    ];
    /**
     * @var array 回调数据组装
     * 如果需要适应其他三方 需要在这里制定规范 屏蔽三方的差异 对业务处理的影响
     */
    public  $event_data = [
        'order_id'=>'',        //订单id
        'order_status'=>'',    //订单状态
        'order_amount'=>'',    //订单金额
        'order_time'  =>'',    //订单交易时间
    ];
    /**
     * @var string 回调时的应答数据
     * 应答和业务无关
     */
    public $event_result;



    /**
     * 组装提交数据，包括签名等等处理工作
     * 输出一个数组 业务层根据 请求类型组装页面 进行提交
     * @return  Array
     */
    public function  submit(){

    }

    /**
     * 回调数据处理，包含身份校验，组装回调数据
     * @return  boolean
     */
    public function event(Array $data){

    }

    /**
     * 用于获取订单的订单id
     * @return string
     */
    public function getOrderid(Array $data){

    }
}