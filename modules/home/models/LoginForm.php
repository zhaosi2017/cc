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
class LoginForm extends Model
{

    public $username;
    public $password;
    public $code;
    private $identity = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password','code'], 'required'],
            ['username', 'email'],
            ['username', 'validateAccount'],
            ['password', 'validatePassword'],
            ['code', 'captcha', 'message'=>'验证码输入不正确', 'captchaAction'=>'/home/login/captcha'],
//            ['code', 'captcha', 'message'=>'验证码输入不正确，请重新输入！3次输入错误，账号将被锁定1年！', 'captchaAction'=>'/login/default/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'code'     => '验证码',
        ];
    }


    public function validateAccount($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();
            if(!$identity){
                $this->addError($attribute, '用户名不存在。');
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();
            if (!Yii::$app->getSecurity()->validatePassword($this->password, $identity->password)) {
                $this->addError($attribute, '密码错误。');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        return Yii::$app->user->login($this->getIdentity());
    }

    public function forbidden()
    {

        $checkLoginIp = LoginLogs::find()
            ->where(['login_ip' => Yii::$app->request->getUserIP()])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if($checkLoginIp){
            $expire = 3600;
            $unlockTime = strtotime($checkLoginIp['login_time'])+$expire;
            if($checkLoginIp['status'] == 4 && $_SERVER['REQUEST_TIME'] < $unlockTime){//锁ip
                return ['lock_type' => 'IP','unlock_time' => date('Y-m-d H:i:s',$unlockTime)];
            }
        }

        $checkLoginAccount = false;
        if($this->getIdentity()){
            $checkLoginAccount = LoginLogs::find()
                ->where(['uid' => $this->getIdentity()->id])
                ->orderBy(['id' => SORT_DESC])
                ->limit(6)
                ->all();
        }


        if($checkLoginAccount){
            $count = 0;
            $countCode = 0;
            foreach ($checkLoginAccount as $k=>$v){
                if($v['status'] == 1){
                    return false;
                }

                //密码错误
                if($v['status'] == 2){
                    ++$count;
                    if($count==4){
                        $unlockAdTime = strtotime($v['login_time'])+1800;
                        if($_SERVER['REQUEST_TIME'] < $unlockAdTime){
                            return ['lock_type' => '账号','unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
                        }
                    }
                    if($count==5){
                        $unlockAdTime = strtotime($v['login_time'])+3600;
                        if($_SERVER['REQUEST_TIME'] < $unlockAdTime){
                            return ['lock_type' => 'ad','unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
                        }
                    }
                    if($count==6){
                        $unlockAdTime = strtotime('+1 year');
                        if($_SERVER['REQUEST_TIME'] < $unlockAdTime){
                            return ['lock_type' => '账号','unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
                        }
                    }

                }

                //验证码错误
                if($v['status'] == 3){
                    ++ $countCode;
                    if($countCode==3){
                        $unlockAdTime = strtotime('+1 year');
                        if($_SERVER['REQUEST_TIME'] < $unlockAdTime){
                            return ['lock_type' => '账号','unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
                        }
                    }

                }

            }
        }

        return false;
    }

    public function preLogin()
    {
        if($this->validate(['username','password'])){
            return true;
        }
        return false;
    }

    //写入登录日志
    public function afterValidate()
    {
        $errors = $this->getErrors();

        if(isset($errors['username'])){
            if(!$this->writeLoginLog(4)){
                parent::afterValidate();
            }
        }

        if(isset($errors['password'])){
            if(!$this->writeLoginLog(2)){
                parent::afterValidate();
            }
        }

        if(isset($errors['code'])){
            if(!$this->writeLoginLog(3)){
                parent::afterValidate();
            }
        }

        parent::afterValidate();
    }

    public function writeLoginLog($status)
    {
        $loginLog = new LoginLogs();
        $loginLog->login_ip = Yii::$app->request->getUserIP();
        $loginLog->status = $status;
        $loginLog->login_time = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
        $loginLog->uid = $this->getIdentity() ? $this->getIdentity()->id : 0;
        return $loginLog->save();
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
