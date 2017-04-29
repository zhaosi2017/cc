<?php

namespace app\modules\home\models;

use Yii;

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active_call_uid' => '主叫账号',
            'unactive_call_uid' => '被叫账号',
            'call_by_same_times' => '被同一人呼叫次数',
            'type' => '电话类型',
            'contact_number' => '主叫电话',
            'unactive_contact_number' => '呼叫电话',
            'status' => '呼叫状态',
            'call_time' => '呼叫时间',
            'active_account' => '主叫账号',
            'unactive_account' => '被叫账号',
            'active_nickname' => '主叫昵称',
            'unactive_nickname' => '被叫昵称',
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
            '1' => '完成',
            '2' => '超时',
            '3' => '拒绝',
            '4' => '忙',
            '5' => '失败',
            '6' => '没有回答',
        ];
    }

}
