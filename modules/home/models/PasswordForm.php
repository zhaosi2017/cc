<?php

namespace app\modules\home\models;

use yii\base\Model;
use Yii;
use app\modules\home\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PasswordForm extends Model
{
    public $password;
    public $newPassword;
    public $rePassword;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['rePassword', 'password', 'newPassword'], 'required'],
            [['rePassword', 'password', 'newPassword'], 'string'],
            ['rePassword', 'compare', 'compareAttribute'=>'newPassword','message'=>'两次密码不一致'],
            ['newPassword', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/', 'message'=>'密码格式错误'],
            ['rePassword', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/', 'message'=>'密码格式错误'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        $identity = (Object) Yii::$app->user->identity;
        if(!Yii::$app->security->validatePassword($this->password, $identity->password)){
            $this->addError($attribute, '原密码错误');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => '原密码',
            'newPassword' => '新密码',
            'rePassword' => '重复输入',
        ];
    }

    public function updateSave()
    {
        if($this->validate()){
            if(Yii::$app->user->id){
                $user = User::findOne(Yii::$app->user->id);
                $user->password = $this->newPassword;
                return $user->save();
            }
            Yii::$app->getSession()->setFlash('error', '操作失败');
        }
        return false;
    }

}
