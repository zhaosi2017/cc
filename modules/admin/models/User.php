<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $password
 * @property string $account
 * @property string $nickname
 * @property integer $un_call_number
 * @property integer $un_call_by_same_number
 * @property integer $long_time
 * @property integer $country_code
 * @property string $phone_number
 * @property string $urgent_contact_number_one
 * @property integer $urgent_contact_one_country_code
 * @property integer $urgent_contact_number_two
 * @property integer $urgent_contact_two_country_code
 * @property string $urgent_contact_person_one
 * @property string $urgent_contact_person_two
 * @property string $telegram_number
 * @property integer $telegram_country_code
 * @property string $potato_number
 * @property integer $potato_country_code
 * @property integer $reg_time
 * @property integer $role_id
 * @property integer $status
 */
class User extends CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'nickname', 'urgent_contact_person_one', 'urgent_contact_person_two'], 'string'],
            [['un_call_number', 'un_call_by_same_number', 'long_time', 'country_code', 'urgent_contact_one_country_code', 'urgent_contact_number_two', 'urgent_contact_two_country_code', 'telegram_country_code', 'potato_country_code', 'reg_time', 'role_id'], 'integer'],
            [['auth_key', 'password', 'phone_number', 'urgent_contact_number_one', 'telegram_number', 'potato_number'], 'string', 'max' => 64],
            [[ 'status'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password' => 'Password',
            'account' => 'Account',
            'nickname' => 'Nickname',
            'un_call_number' => '被叫总次数',
            'un_call_by_same_number' => '被同一人呼叫次数',
            'long_time' => '时间设置',
            'country_code' => 'Country Code',
            'phone_number' => 'Phone Number',
            'urgent_contact_number_one' => 'Urgent Contact Number One',
            'urgent_contact_one_country_code' => 'Urgent Contact One Country Code',
            'urgent_contact_number_two' => 'Urgent Contact Number Two',
            'urgent_contact_two_country_code' => 'Urgent Contact Two Country Code',
            'urgent_contact_person_one' => 'Urgent Contact Person One',
            'urgent_contact_person_two' => 'Urgent Contact Person Two',
            'telegram_number' => 'Telegram Number',
            'telegram_country_code' => 'Telegram Country Code',
            'potato_number' => 'Potato Number',
            'potato_country_code' => 'Potato Country Code',
            'reg_time' => 'Reg Time',
            'role_id' => 'Role ID',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->account = Yii::$app->security->decryptByKey(base64_decode($this->account), Yii::$app->params['inputKey']);
        $this->nickname && $this->nickname = Yii::$app->security->decryptByKey(base64_decode($this->nickname), Yii::$app->params['inputKey']);
        $this->urgent_contact_person_one && $this->urgent_contact_person_one = Yii::$app->security->decryptByKey(base64_decode($this->urgent_contact_person_one), Yii::$app->params['inputKey']);
        $this->urgent_contact_person_two && $this->urgent_contact_person_two = Yii::$app->security->decryptByKey(base64_decode($this->urgent_contact_person_two), Yii::$app->params['inputKey']);
    }

}
