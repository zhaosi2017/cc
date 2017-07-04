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
            ['rePassword', 'compare', 'compareAttribute'=>'newPassword','message'=>Yii::t('app/models/PasswordForm' , 'Two passwords are inconsistent')/*'两次密码不一致'*/],
            ['newPassword', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/', 'message'=>Yii::t('app/models/PasswordForm' , 'Password format is incorrect')/*'密码格式错误'*/],
            ['rePassword', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/', 'message'=>Yii::t('app/models/PasswordForm' , 'Password format is incorrect')/*'密码格式错误'*/],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        $identity = (Object) Yii::$app->user->identity;
        if(!Yii::$app->security->validatePassword($this->password, $identity->password)){
            $this->addError($attribute, Yii::t('app/models/PasswordForm' ,'he original password is incorrect')/*'原密码错误'*/);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app/models/PasswordForm' ,'old password'),//'原密码',
            'newPassword' => Yii::t('app/models/PasswordForm' ,'new password'),//'新密码',
            'rePassword' => Yii::t('app/models/PasswordForm' ,'Repeat input'),//'重复输入',
        ];
    }

    public function updateSave()
    {
        if($this->validate()){
            if(Yii::$app->user->id){
                $user = User::findOne(Yii::$app->user->id);
                $posts = Yii::$app->request->post();
                $posts['PasswordForm']['password'] = $this->newPassword;
                Yii::$app->request->setBodyParams($posts);
                $user->password = $this->newPassword;
                return $user->save();
            }
            Yii::$app->getSession()->setFlash('error', Yii::t('app/models/PasswordForm' ,'operation failed')/*'操作失败'*/);
        }
        return false;
    }

}
