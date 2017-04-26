<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "manager".
 *
 * @property integer $id
 * @property string $account
 * @property string $nickname
 * @property integer $role_id
 * @property integer $status
 * @property string $remark
 * @property string $login_ip
 * @property integer $create_id
 * @property integer $update_id
 * @property integer $create_at
 * @property integer $update_at
 */
class Manager extends \app\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'nickname', 'remark'], 'string'],
            [['role_id', 'status', 'create_id', 'update_id', 'create_at', 'update_at'], 'integer'],
            [['login_ip'], 'string', 'max' => 64],
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
            'role_id' => 'Role ID',
            'status' => 'Status',
            'remark' => 'Remark',
            'login_ip' => 'Login Ip',
            'create_id' => 'Create ID',
            'update_id' => 'Update ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return ManagerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ManagerQuery(get_called_class());
    }
}
