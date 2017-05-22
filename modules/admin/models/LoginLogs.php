<?php

namespace app\modules\admin\models;

use Yii;

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
class LoginLogs extends \app\models\CActiveRecord
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

    /**
     * @inheritdoc
     * @return LoginLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoginLogsQuery(get_called_class());
    }
}
