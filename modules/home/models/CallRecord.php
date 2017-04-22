<?php

namespace app\modules\home\models;

use Yii;

/**
 * This is the model class for table "call_record".
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
            'active_call_uid' => 'Active Call Uid',
            'unactive_call_uid' => 'Unactive Call Uid',
            'call_by_same_times' => 'Call By Same Times',
            'type' => 'Type',
            'contact_number' => 'Contact Number',
            'status' => 'Status',
            'call_time' => 'Call Time',
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
