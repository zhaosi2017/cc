<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/3
 * Time: 下午3:08
 * 资金服务组
 * 处理用户的充值，使用等等和资金相关的业务
 */
namespace app\modules\home\servers\FinalService;

use app\modules\home\models\FinalChangeLog;
use app\modules\home\models\FinalMerchantInfo;
use app\modules\home\models\FinalOrder;
use app\modules\home\models\User;
use Yii;
use yii\db\Transaction;

class FinalService{

    /**
     *  创建一个订单 并请求数据
     */
    public function CreateOrder($order_type , $amount = 0){

        if(!aiyi::checkType($order_type)){
            return false;
        }
        if(!is_numeric($amount) && $amount<0)
        {
            return false;
        }

        $Merchant = FinalMerchantInfo::find(['status'=>FinalMerchantInfo::MERCHANT_STATUS_OPEN])
                                        ->where('recharge_type &'.$order_type)
                                        ->one();

        if(empty($Merchant)){
            return false;
        }
        $conversion  = new RateConversion();
        $conversion->source = 'CNY';
        $conversion->target = 'USD';
        $rate = $conversion->conversion();
        if($rate === false ){
            $rate = 0.1500;
        }
        $order = new FinalOrder();
        $order->amount  = $amount;
        $order->real_amount = $amount * $rate;
        $order->rate = $rate;
        $order->order_id = FinalOrder::uuid();
        $order->user_id = Yii::$app->user->id ;
        $order->status  = FinalOrder::ORDER_STATUS_SUBMIT;
        $order->merchant_id = $Merchant->id;
        $order->time = time();
        if(!$order->save()){
            return false;
        }
        $service = new aiyi();
        $service->Merchant = $Merchant;
        $service->request_data['order_id']     = $order->order_id;
        $service->request_data['order_amount'] = $order->amount;
        $service->request_data['order_type']   = $order_type;
        
        $result['order_id']     = $order->order_id;
        $result['data']         = $service->submit();
        $result['uri']          = $service->pay_uri;
        $result['request_type'] = $service->request_type;
        return $result;
    }

    /**
     * @param array $data
     * @return string
     * 处理回调
     */
    public function Event(Array $data){

            $service = new aiyi();
            $order_id = $service->getOrderid($data);
            $order = FinalOrder::findOne(['order_id'=>$order_id]);
            if(empty($order) ){                  //订单不存在
                return $service->event_result;
            }
            $Merchant = FinalMerchantInfo::findOne(['id'=>$order->merchant_id]);
            if(empty($Merchant)){          //商户不存在
                return $service->event_result;
            }
            $service->Merchant = $Merchant;

            if(!$service->event($data) ){ //数据解析失败（签名错误 ，商户错误 ,状态错误）
                return $service->event_result;
            }
            if($order->amount != $service->event_data['order_amount']
               || $order->status > $service->event_data['order_status']
               || $order->status !== FinalOrder::ORDER_STATUS_SUBMIT){   //订单检测
                return $service->event_result;
            }

            $order->status = $service->event_data['order_status'];
            Yii::$app->db->beginTransaction(Transaction::READ_COMMITTED);
            $transaction  = Yii::$app->db->getTransaction();
            if($order->save()){
                $transaction->rollBack();
                return $service->event_result;
            }
            if($service->event_data['order_status'] == FinalOrder::ORDER_STATUS_SUCCESS){
                $Merchant->amount +=  $service->event_data['order_amount'];
                if(!$this->Recharge($order->user_id ,$order->real_amount,'充值帐变' , $Merchant )){
                    $transaction->rollBack();
                }
            }
            $transaction->commit();
            return $service->event_result;
    }



    /**
     *充值
     */
    public function Recharge($user_id , $amount , $commit ='' ,FinalMerchantInfo $merchantInfo ){

        Yii::$app->db->beginTransaction(Transaction::READ_COMMITTED);
        $transaction = Yii::$app->db->getTransaction();

        $user_model = User::findOne($user_id);
        /********用户空 ，充值账号保存失败 *****/
        if(empty($user_model) || !$merchantInfo->save() ){
            $transaction->rollBack();
            return false;
        }
        if($amount <= 0){
            $transaction->rollBack();
            return true;
        }
        $change = new FinalChangeLog();
        $change->before = $user_model->amount;

        $user_model->amount = $user_model->amount + $amount;
        if(!$user_model->save()){
            $transaction->rollBack();
            return false;
        }
        $change->after = $user_model->amount;
        $change->user_id = $user_id;
        $change->amount = $amount;
        $change->comment = $commit;
        $change->change_type = FinalChangeLog::FINAL_CHANGE_TYPE_RECHARGE;
        if(!$change->save()){
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;

    }

    /**
     * @param $user_id
     * @param $amount
     * @param $commit
     * @param $type   from FinalChangeLog::$final_change_type
     * 提供给外部调用 用户使用资金
     */
    public function apply($user_id , $amount , $type, $commit ='' ){
        if(!array_key_exists($type, FinalChangeLog::$final_change_type))
        {
            return false;
        }

        Yii::$app->db->beginTransaction(Transaction::READ_COMMITTED);
        $transaction = Yii::$app->db->getTransaction();


        $user_model = User::findOne($user_id);
        if(empty($user_model)){
            $transaction->rollBack();
            return false;
        }
        if($amount <= 0){
            $transaction->rollBack();
            return true;
        }

        $change = new FinalChangeLog();
        $change->before = $user_model->amount;

        $user_model->amount = $user_model->amount - $amount;
        if(!$user_model->save()){
            $transaction->rollBack();
            return false;
        }

        $change->after = $user_model->amount;
        $change->user_id = $user_id;
        $change->amount = $amount;
        $change->comment = $commit;
        $change->change_type = $type;
        if(!$change->save()){
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }






}