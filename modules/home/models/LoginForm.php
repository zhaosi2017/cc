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
    public $pwd;
    public $code;
    private $identity = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'pwd','code'], 'required'],
            ['username', 'email','message'=>'账号不存在,请核实'],
            ['username', 'validateAccount'],
            ['pwd', 'validatePassword'],
            ['code', 'captcha', 'message'=>'验证码错误', 'captchaAction'=>'/home/login/captcha'],
//            ['code', 'captcha', 'message'=>'验证码输入不正确，请重新输入！3次输入错误，账号将被锁定1年！', 'captchaAction'=>'/login/default/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '邮箱',
            'pwd' => '密码',
            'code'     => '验证码',
        ];
    }


    public function validateAccount($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();
            if(!$identity){
                $this->addError($attribute, '账号不存在，请核实');
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
            if (!Yii::$app->getSecurity()->validatePassword($this->pwd, $identity->password)) {
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
         if($this->validate(['username','pwd','code'])){
            return Yii::$app->user->login($this->getIdentity());
        }
        return false;
        
    }

    /**
     * 用户登录记录ip和时间
     */
    public function recordIp()
    {

        $user = User::findOne($this->getIdentity()->id);
        $user->login_ip = Yii::$app->request->getUserIP();
        $user->login_time = $_SERVER['REQUEST_TIME'];
        return $user->update();
    }

    public function checkLock()
    {
        $redis = Yii::$app->redis;
        $key = $this->username.'-'.'homenum';
        $num =  $redis->HGET($key,'num') ;
        $flag = $redis->HGET($key,'flag');
        $exprietime = $redis->hget($key,'exprietime'); 
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
        $flag = Yii::$app->redis->hget($this->username.'-'.'homenum','flag');
        $num = Yii::$app->redis->hget($this->username.'-'.'homenum','num');
        if($num == 1){
            $this->addError('username', '用户再错两次账号将被冻结三十分钟');
        }
        if($flag == 1){
            $this->addError('username', '用户已被冻结30分钟，30分钟后再错将冻结24小时');
        }
        if($flag == 2){
            $this->addError('username', '用户已被冻结24小时');
        }
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
        
        if($this->validate(['username','pwd','code'])){
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

        if(isset($errors['pwd'])){
            $this->recordLoginError();
            if(!$this->writeLoginLog(2)){
                parent::afterValidate();
            }
        }

        if(isset($errors['code'])){
            if(!$this->writeLoginLog(3)){
                parent::afterValidate();
            }
        }

        if(empty($errors)){
            $this->writeLoginLog(1);
        }

        

        parent::afterValidate();
    }

    public function  recordLoginError()
    {
        if($this->username)
        {
            $redis = Yii::$app->redis;
            $key  = $this->username.'-homenum';
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
        $loginLog = new LoginLogs();
        $ip = Yii::$app->request->getUserIP();
        $loginLog->login_ip = $ip;
        $loginLog->status = $status;
        $loginLog->login_time = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
        $loginLog->uid = $this->getIdentity() ? $this->getIdentity()->id : 0;
        $loginLog->address =  Yii::$app->ip2region->getRegion($ip);
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

    /**
     * 用户修改密码后，删除该用户登录时错误密码的记录数
     */
    public function deleteLoginNum()
    {
        $redis = Yii::$app->redis;
        $key = $this->username.'-homenum' ; 
        if($redis->exists($key))
        {
            $redis->del($key);
        }
    }
}
