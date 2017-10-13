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


    CONST Record_Status_Success = 0;         //通话成功
    CONST Record_Status_Fail    = 1;         //通话失败
    CONST Record_Status_Busy    = 3;        //用户忙
    CONST Record_Status_NoAnwser= 5;        //无应答
    /**
     * @var int 正常呼叫
     */
    CONST Record_Type_none      = 0;         //正常呼叫
    /**
     * @var int 紧急联系人呼叫
     */
    CONST Record_Type_emergency = 1;         //紧急联系人呼叫

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
            [['long_time','total_nums','active_code'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active_call_uid' => Yii::t('app/models/CallRecord' , 'Call account id'),//'主叫账号id',
            'unactive_call_uid' => Yii::t('app/models/CallRecord','Called account id') ,//'被叫账号id',
            'call_by_same_times' => Yii::t('app/models/CallRecord','The number of calls by the same person'),//'被同一人呼叫次数',
            'typeData' => Yii::t('app/models/CallRecord' , 'Phone type'),//'电话类型',
            'contact_number' => Yii::t('app/call-record/index','Call phone'),
            'unactive_contact_number' => Yii::t('app/call-record/index','Called phone'),
            'typeData'=> Yii::t('app/call-record/index','Call type'),
            'status' => Yii::t('app/call-record/index','Call status'),
            'statusData' =>Yii::t('app/call-record/index','Call status'),
            'call_time' => Yii::t('app/call-record/index','Call time'),
            'active_account' => Yii::t('app/call-record/index','Call account'),
            'unactive_account' => Yii::t('app/call-record/index','Called account'),
            'active_nickname' => Yii::t('app/call-record/index','Call nickname'),
            'unactive_nickname' => Yii::t('app/call-record/index','Called nickname'),
            'long_time'=>Yii::t('app/models/CallRecord','Time period'),//'时间周期(分)',
            'total_nums'=>Yii::t('app/models/CallRecord','Total number of calls'),//'呼叫总次数'
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
            '0' => Yii::t('app/call-record/index','Success'),
            '1' => Yii::t('app/call-record/index','Failure'),
            '2' => Yii::t('app/call-record/index','Refuse'),
            '3' => Yii::t('app/call-record/index','Busy'),
            '4' => Yii::t('app/call-record/index','Time out'),
            '5' => Yii::t('app/call-record/index','No answer'),
        ];
    }

    public function getStatusListBySearch(){
        return [
            '0' =>  Yii::t('app/call-record/index','Success'),
            '1' =>  Yii::t('app/call-record/index','Failure'),
            '3' => Yii::t('app/call-record/index','Busy'),
            '5' => Yii::t('app/call-record/index','No answer'),
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
            '0' => Yii::t('app/call-record/index','Called phone'),
            '1' => Yii::t('app/call-record/index','Called emergency call'),
            '3' => Yii::t('app/call-record/index','Busy'),
            '5' => Yii::t('app/call-record/index','No answer'),
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
