<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/3
 * Time: 下午3:17
 * 用户充值三方日志记录
 */

namespace app\modules\home\models;
use \app\models\CActiveRecord;

class FinalMutualLog extends CActiveRecord{

    const MUTUAL_TYPE_BEGIN                = 1;  //发起充值
    const MUTUAL_TYPE_BEGIN_RETURN         = 2;  //发起充值的返回
    const MUTUAL_TYPE_CALLBACK             = 3;  //充值回调
    const MUTUAL_TYPE_CALLBACK_RETURN      = 4;  //充值回调返回
    static public $final_mutual_type = [
        self::MUTUAL_TYPE_BEGIN =>'发起充值',
        self::MUTUAL_TYPE_BEGIN_RETURN  =>'充值返回',
        self::MUTUAL_TYPE_CALLBACK =>'充值回调',
        self::MUTUAL_TYPE_CALLBACK_RETURN  =>'充值回调返回',

    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'final_mutual_log';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',  //id
            'interface_name' => '接口名',//接口名,
            'data' => '交互数据' ,//交互数据,
            'time' => '交互时间',//交互时间,
            'type' => '交互类型',//交互类型',

        ];
    }

}