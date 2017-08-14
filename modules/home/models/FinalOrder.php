<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/11
 * Time: 下午4:07
 */

namespace app\modules\home\models;
use \app\models\CActiveRecord;

/**
 * Class FinalMerchantInfo
 * @package app\modules\home\models
 * @property integer $order_id
 * @property string  $user_id
 * @property integer $merchant_id
 * @property string  $comment
 * @property integer $time
 * @property float   $status
 * @property integer $amount
 */
class FinalOrder extends  CActiveRecord{

    const ORDER_STATUS_START    = 0;    //下单
    const ORDER_STATUS_SUBMIT   = 1;    //提交
    const ORDER_STATUS_EVENT    = 2;    //回调
    const ORDER_STATUS_SUCCESS  = 4;    //成功
    const ORDER_STATUS_FAIL     = 8;    //失败
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'final_order';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'ID',  //id
            'user_id' => '充值用户',//账号,
            'merchant_id'=>'使用的充值账号', //
            'comment' => '描述',//签名的凭证,
            'time' => '创建时间',//创建时间',
            'status' => '账号的状态' ,//账号的状态,
            'amount' => '金额',//余额,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time','status','user_id','merchant_id'], 'integer'],
            [['order_id'], 'string','max'=>255],
        ];
    }




    public static function uuid()
    {
        $res =  sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        return md5($res);
    }


}
