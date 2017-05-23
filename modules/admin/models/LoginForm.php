<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property $user This property is read-only.
 *
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = null;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            ['username', 'validateAccount'],
            ['password', 'validatePassword'],
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
        ];
    }


    public function validateAccount($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getUser();
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
            $identity = $this->getUser();

            if ($identity && !Yii::$app->getSecurity()->validatePassword($this->password, $identity->password)) {
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
        // 数据格式是否验证通过.
        if ($this->validate()) {
            $this->writeLoginLog(1);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 *30 : 0);
        } else {
            return false;
        }
    }

    //写入登录日志
    public function afterValidate()
    {
        $errors = $this->getErrors();

        /*if(isset($errors['username'])){
            if(!$this->writeLoginLog(4)){
                parent::afterValidate();
            }
        }*/

        if(isset($errors['password'])){
            if(!$this->writeLoginLog(2)){
                parent::afterValidate();
            }
        }

        /*if(isset($errors['code'])){
            if(!$this->writeLoginLog(3)){
                parent::afterValidate();
            }
        }*/

        parent::afterValidate();
    }

    public function writeLoginLog($status)
    {
        $loginLog = new ManagerLoginLogs();
        $loginLog->login_ip = Yii::$app->request->getUserIP();
        $loginLog->status = $status;
        $loginLog->login_time = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
        $loginLog->uid = $this->_user ? $this->_user->id : 0;
        return $loginLog->save();
    }

    /**
     * 获取用户数据.
     *
     * @return null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $accounts = Manager::find()->select(['account', 'id'])->indexBy('id')->column();
            foreach ($accounts as $id => $account){
                $this->username == Yii::$app->security->decryptByKey(base64_decode($account),Yii::$app->params['inputKey']) && $this->_user = Manager::findOne($id);
            }
        }

        return $this->_user;
    }

}
