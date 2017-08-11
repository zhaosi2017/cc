<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/11
 * Time: 下午2:54
 */
namespace app\modules\home\servers\FinalService;

use app\modules\home\models\FinalOrder;

class aiyi extends  AbstractThird{


    public  $service_map = [
        1=>'cibalipay', //兴业支付宝
        2=>'cibweixin',  //兴业微信支付

    ];

    public $event_result = 'SUCCESS';

    public function submit(){
        $result =  [
            'mch_id'=>$this->Merchant->merchant_id,
            'out_trade_no'=>$this->request_data['order_id'],
            'body'=>'recharge',
            'callback_url' =>'',
            'notify_url'=>'',
            'total_fee'=>$this->request_data['order_amount'],
            'service'=>$this->service_map[$this->Merchant->Recharge_type],
            'type'=>0
        ];
        $str = implode('',$result);
        $str.=$this->Merchant->certificate;
        $result['sign'] = md5($str);
        return $result;
    }


    public function getOrderid(Array $data){
        return $data['out_trade_no'];
    }


    public function event(Array $data){

        $str = $data['mch_id'].$data['out_trade_no'].$data['orderid'].$data['total_fee'].$data['service'].$data['result_code'];
        $str .=$this->Merchant->certificate;
        if(md5($str) != $data['sign']){
            return false;
        }
        $this->event_data['order_id']     = $data['out_trade_no'];
        $this->event_data['order_status'] = $data['result_code'] == 0? FinalOrder::ORDER_STATUS_SUCCESS:FinalOrder::ORDER_STATUS_FAIL;
        $this->event_data['order_amount'] = $data['total_fee'];
        $this->event_data['order_time']   = time();
        return true;
    }



}