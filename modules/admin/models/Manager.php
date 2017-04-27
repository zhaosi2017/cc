<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CActiveRecord;

/**
 * This is the model class for table "manager".
 *
 * @property integer $id
 * @property string $auth_key
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
class Manager extends CActiveRecord
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
            [['account', 'nickname', 'remark', 'auth_key'], 'string'],
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
            'account' => '管理员账号',
            'nickname' => '管理员昵称',
            'role_id' => '管理员角色',
            'status' => 'Status',
            'remark' => '备注',
            'login_ip' => 'Login Ip',
            'create_id' => 'Create ID',
            'update_id' => 'Update ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    public function beforeSave($insert)
    {
        $uid = Yii::$app->user->id;
        if($this->isNewRecord){
            $this->create_at = $_SERVER['REQUEST_TIME'];
            $this->update_at = $_SERVER['REQUEST_TIME'];
            $this->create_id = $uid;
            $this->auth_key  = Yii::$app->security->generateRandomString();
        }else{
            $this->update_at = $_SERVER['REQUEST_TIME'];
            $this->update_id = $uid;
        }
        return true;
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
