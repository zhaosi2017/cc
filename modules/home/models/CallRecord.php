<?php

namespace app\modules\home\models;

use Yii;

/**
 * This is the model class for table "call_record"..
 *
 * @property integer $id
 * @property integer $active_call_uid
 * @property integer $unactive_call_uid
 * @property integer $call_by_same_times
 * @property integer $type
 * @property string $contact_number
 * @property integer $status
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
            [['active_call_uid', 'unactive_call_uid', 'call_by_same_times', 'type', 'status', 'call_time'], 'integer'],
            [['contact_number'], 'string', 'max' => 64],
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
            'type' => '呼叫类型',
            'contact_number' => '联系人电话号码',
            'status' => '呼叫状态',
            'call_time' => '呼叫时间',
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
}
