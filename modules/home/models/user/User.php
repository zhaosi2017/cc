<?php

namespace app\modules\home\models\user;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $account
 * @property string $nickname
 * @property integer $un_call_number
 * @property integer $un_call_by_same_number
 * @property integer $long_time
 * @property string $phone_number
 * @property string $urgent_contact_number
 * @property integer $urgent_contact_number_two
 * @property string $urgent_contact_person_one
 * @property string $urgent_contact_person_two
 * @property string $telegram_number
 * @property string $potato_number
 * @property integer $reg_time
 * @property integer $role_id
 */
class User extends \yii\db\ActiveRecord
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
            [['un_call_number', 'un_call_by_same_number', 'long_time', 'urgent_contact_number_two', 'reg_time', 'role_id'], 'integer'],
            [['phone_number', 'urgent_contact_number', 'telegram_number', 'potato_number'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => 'Account',
            'nickname' => 'Nickname',
            'un_call_number' => 'Un Call Number',
            'un_call_by_same_number' => 'Un Call By Same Number',
            'long_time' => 'Long Time',
            'phone_number' => 'Phone Number',
            'urgent_contact_number' => 'Urgent Contact Number',
            'urgent_contact_number_two' => 'Urgent Contact Number Two',
            'urgent_contact_person_one' => 'Urgent Contact Person One',
            'urgent_contact_person_two' => 'Urgent Contact Person Two',
            'telegram_number' => 'Telegram Number',
            'potato_number' => 'Potato Number',
            'reg_time' => 'Reg Time',
            'role_id' => 'Role ID',
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
}
