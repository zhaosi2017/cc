<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/11
 * Time: 下午3:16
 */
namespace app\modules\home\models;
use \app\models\CActiveRecord;

/**
 * Class FinalMerchantInfo
 * @package app\modules\home\models
 * @property integer $id
 * @property string  $merchant_id
 * @property integer $sign_type
 * @property string  $certificate
 * @property integer $status
 * @property float   $amount
 * @property integer $time
 * @property integer $recharge_type
 */
class FinalMerchantInfo extends  CActiveRecord{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'final_merchant_info';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',  //id
            'merchant_id' => '账号',//账号,
            'recharge_type'=>'', //支持支付的类型   这里具体的数字定义交给具体的支付平台接口处理
            'sign_type' => '签名的类型' ,//签名的类型,
            'certificate' => '签名的凭证',//签名的凭证,
            'time' => '创建时间',//创建时间',
            'status' => '账号的状态' ,//账号的状态,
            'amount' => '余额',//余额,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sign_type', 'time','status','recharge_type'], 'integer'],
            [['certificate' ,'merchant_id'], 'string','max'=>255],
            [['amount'],'decimal'],
        ];
    }



}