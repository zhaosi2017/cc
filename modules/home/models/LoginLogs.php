<?php

namespace app\modules\home\models;

use app\models\CActiveRecord;

/**
 * This is the model class for table "login_logs".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $status
 * @property string $login_time
 * @property string $login_ip
 * @property string $unlock_time
 * @property integer $unlock_uid
 */
class LoginLogs extends CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'login_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'status', 'unlock_uid'], 'integer'],
            [['login_time', 'unlock_time'], 'safe'],
            [['login_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'status' => 'Status',
            'login_time' => 'Login Time',
            'login_ip' => 'Login Ip',
            'unlock_time' => 'Unlock Time',
            'unlock_uid' => 'Unlock Uid',
        ];
    }

    public function getStatuses()
    {
        return [
            0 => '登录成功',
            1 => '已解锁',
            2 => '密码错误',
            3 => '验证错误',
            4 => 'IP锁定中',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
}
