<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/11
 * Time: 下午3:16
 */
namespace app\modules\home\models;
use \app\models\CActiveRecord;
use app\modules\home\servers\FinalService\aiyi;

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


    const MERCHANT_STATUS_OPEN  = 1;
    const MERCHANT_STATUS_CLOSE = 2;

    public static $merchant_status_map = [
        self::MERCHANT_STATUS_OPEN =>'开启',
        self::MERCHANT_STATUS_CLOSE=>'关闭'
    ];

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
            'recharge_type'=>'支付类型', //支持支付的类型   这里具体的数字定义交给具体的支付平台接口处理
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
            [['amount'],'double'],
        ];
    }

    /**
     * 获取支付类型对应的名称
     * 如果有其他支付渠道的话 读取数据库的配置
     * @param $type  支付类型
     * @return string
     */
    public function getChannelName($type = null){

      $result='';
      foreach(aiyi::$service_name_map as $key=>$value){
          if($type == null ){
              $result .= ','.$value;
          }elseif($key == $type){
              return $value;
          }elseif($type & $key ){
              $result .= ','.$value;
          }
      }
      return trim($result,',');
    }

}