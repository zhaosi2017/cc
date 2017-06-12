<?php

namespace app\modules\home\models;

use Yii;
use app\modules\home\models\User;

/*
* @property integer $id
* @property integer $active_call_uid
* @property integer $unactive_call_uid
* @property string $active_account
* @property string $unactive_account
* @property string $active_nickname
* @property string $unactive_nickname
* @property integer $call_by_same_times
* @property integer $type
* @property string $contact_number
* @property string $unactive_contact_number
* @property integer $status
* @property integer $record_status
* @property integer $call_time
 */
class CallRecord extends \app\models\CActiveRecord
{
    public $total_nums;
    public $long_time;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_call_uid', 'unactive_call_uid', 'call_by_same_times', 'type', 'status', 'record_status', 'call_time'], 'integer'],
            [['active_account', 'unactive_account', 'unactive_contact_number'], 'required'],
            [['active_account', 'unactive_account'], 'string', 'max' => 100],
            [['active_nickname', 'unactive_nickname'], 'string', 'max' => 50],
            [['contact_number'], 'string', 'max' => 64],
            [['unactive_contact_number'], 'string', 'max' => 15],
            [['long_time','total_nums'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active_call_uid' => '主叫账号id',
            'unactive_call_uid' => '被叫账号id',
            'call_by_same_times' => '被同一人呼叫次数',
            'typeData' => '电话类型',
            'contact_number' => '主叫电话',
            'unactive_contact_number' => '联系电话',
            'type'=> '电话类型',
            'status' => '呼叫状态',
            'statusData' => '呼叫状态',
            'call_time' => '呼叫时间',
            'active_account' => '主叫账号',
            'unactive_account' => '被叫账号',
            'active_nickname' => '主叫昵称',
            'unactive_nickname' => '被叫昵称',
            'long_time'=>'时间周期(分)',
            'total_nums'=>'呼叫总次数'
        ];
    }

    /**
     * @inheritdoc
     * @return CallRecordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallRecordQuery(get_called_class());
    }

    /**
     * 获取状态列表.
     */
    public function getStatusList()
    {
        return [
            '0' => '完成',
            '1' => '超时',
            '2' => '拒绝',
            '3' => '忙',
            '4' => '失败',
            '5' => '没有回答',
        ];
    }

    public function getStatusData()
    {
        $statusArr = $this->getStatusList();
        return $statusArr[$this->status];
    }

    public function getTypeList()
    {
        return [
            '0' => '被叫联系电话',
            '1' => '被叫紧急联系电话'
        ];
    }

    public function getTypeData()
    {
        $typeArr = $this->getTypeList();
        return $typeArr[$this->type];
    }

    public function getUsers()
    {
        return $this->hasOne(User::className(), ['id' => 'active_call_uid']);
    }
}
