<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/3
 * Time: 下午3:17
 * 用户资金变化日志记录
 */
namespace app\modules\home\models;
use \app\models\CActiveRecord;


class FinalChangeLog extends CActiveRecord{

    const FINAL_CHANGE_TYPE_RECHARGE = 1; //充值
    const FINAL_CHANGE_TYPE_CONSUME  = 2; //消费

    static public $final_change_type = [
        self::FINAL_CHANGE_TYPE_RECHARGE =>'充值',
        self::FINAL_CHANGE_TYPE_CONSUME  =>'消费',

    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'final_change_log';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',  //id
            'change_type' => '帐变类型',//帐变类型,
            'amount' => '帐变金额' ,//帐变金额,
            'time' => '帐变时间',//帐变时间,
            'user_id' => '帐变发生人',//帐变发生人',
            'comment' => '说明' ,//说明,
            'before' => '帐变之前金额',//帐变之前金额,
            'after' => '帐变之后金额',//帐变之后金额',
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'change_type', 'time', 'user_id'], 'integer'],
            [['comment'], 'string','max'=>255],
            [['amount' ,'before', 'after'],'decimal'],
        ];
    }

}