<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class EmailForm extends Model
{

    public $username;
    public $code;
    private $identity = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username','code'], 'required'],
            ['username', 'validateAccount'],
            ['code', 'checkCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '邮箱',
            'code'     => '验证码',
        ];
    }

    public function checkCode($attribute)
    {
        $session = Yii::$app->session;
        $key = $this->username.'bindemail';
        $email = $session[$key];
        if (empty($this->code) ||  $this->code !=$email){
             $this->addError('code', Yii::t( 'app/models/EmailForm', 'Verification code error')/*'验证码错误'*/);
        }else{
            $session->remove($key);
        }
    }


    public function validateAccount($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();
            if(isset($identity->id ) && $identity->id != Yii::$app->user->id){
                $this->addError($attribute, Yii::t( 'app/models/EmailForm', 'account already exists')/*'账号已存在'*/);
            }
        }
    }

    public function getIdentity()
    {
        if($this->identity === false){
            $accounts = User::find()->select(['id','account'])->indexBy('account')->column();
            foreach ($accounts as $account => $id){
                $this->username == Yii::$app->security->decryptByKey(base64_decode($account),Yii::$app->params['inputKey'])
                && $this->identity = User::findOne($id);
            }
        }
        return $this->identity;
    }



}
