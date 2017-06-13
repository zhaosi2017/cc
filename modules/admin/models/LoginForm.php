<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use app\modules\admin\models\Manager;

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
    public $code;
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
            ['code', 'captcha', 'message'=>'验证码输入不正确', 'captchaAction'=>'/admin/login/captcha'],
            ['username','checkAccountStatus'],
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

    public function checkAccountStatus()
    {
       $user = $this->getUser();
       if(isset($user->status) && $user->status != Manager::NORMAL_STATUS)
       {
             $this->addError('username','用户已被冻结或作废,请联系管理员');
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
            $this->recordLoginError();
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
    /**
     * 登陆错误到达指定条件后的处理
     */
    public function  recordLoginError()
    {
        if($this->username)
        {
            $redis = Yii::$app->redis;
            $key = $this->username.'-'.'adminnum';
            $time = time();
            $redis->hincrby($key, 'num', 1);
            $num = $redis->hget($key,'num');

            switch ($num) {
                case 1:
                        $redis->expire($key,60*60);
                        break;
                case 3:
                        $redis->hset($key,'exprietime',$time + Yii::$app->params['login_flag_time1']); //30分钟
                        $redis->hset($key,'flag',1);
                        $redis->expire($key,Yii::$app->params['login_flag_time1']+Yii::$app->params['login_flag_time2']);
                        break;

                case 4:
                        $redis->hset($key,'exprietime', $time + Yii::$app->params['login_flag_time3']); //24小时
                        $redis->hset($key,'flag',2);
                        $redis->expire($key,Yii::$app->params['login_flag_time3']);
                        break;
            }
           
        }
        
    }

    public function writeLoginLog($status)
    {
        $loginLog = new ManagerLoginLogs();
        $ip = Yii::$app->request->getUserIP();
        $loginLog->login_ip = $ip;
        $loginLog->status = $status;
        $loginLog->login_time = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
        $loginLog->uid = $this->_user ? $this->_user->id : 0;
        $loginLog->address =  Yii::$app->ip2region->getRegion($ip);
        return $loginLog->save();
    }
    /**
     * 检查用户是否锁定
     */
    public function checkLock()
    {

        $redis = Yii::$app->redis;
        $key = $this->username.'-'.'adminnum';
        $num =  $redis->hget($key,'num') ;
        $exprietime = $redis->hget($key,'exprietime');
        $flag = $redis->hget($key,'flag');
        
        if( $num > 1 ){
            if(( $flag && $exprietime > time()) ){
                $flag == 1 &&  $message ='该用户已被冻结30分钟';
                $flag == 2 &&  $message ='该用户已被冻结24小时';
                $this->addError('username',  $message);
                return true;
            }   
        }
        return false;

    }


     public function afterCheckLock()
    {
        $flag = Yii::$app->redis->hget($this->username.'-'.'adminnum','flag');
        $num = Yii::$app->redis->hget($this->username.'-'.'adminnum','num');
        if($num == 1){
            $this->addError('username', '用户再错两次账号将被冻结三十分钟');
        }
        if($flag == 1){
            $this->addError('username', '用户已被冻结30分钟,30分钟后再错误直接冻结24小时');
        }
        if($flag == 2){
            $this->addError('username', '用户已被冻结24小时');
        }
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
