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
class LoginPhoneForm extends Model
{

    public $username;
    public $pwd;
    public $code;
    private $identity = false;
    public $country_code;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['country_code','required'],
            ['country_code','match', 'pattern' => '/(^[0-9])+/', 'message' => Yii::t("app/login","Country code number must be number")],
            [['username', 'pwd','code'], 'required'],

            ['username', 'validateAccount'],
            ['pwd', 'match', 'pattern' => '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,}$/', 'message'=>Yii::t('app/models/register-form','Password format is incorrect')],
            ['pwd', 'validatePassword'],
            ['code', 'captcha', 'message'=>Yii::t('app/models/LoginForm' , 'Verification code error')/*'验证码错误'*/, 'captchaAction'=>'/home/login/captcha'],
//            ['code', 'captcha', 'message'=>'验证码输入不正确，请重新输入！3次输入错误，账号将被锁定1年！', 'captchaAction'=>'/login/default/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app/login','Phone'),//'电话',
            'pwd' => Yii::t('app/models/LoginForm','Password'),//'密码',
            'code'     => Yii::t('app/models/LoginForm','Verification code'),//'验证码',
            'country_code' => Yii::t('app/models/phone-register-form','Country code'),
        ];
    }


    public function validateAccount($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getUserInfo();
            if(!$identity){
                $this->addError($attribute, Yii::t('app/models/LoginForm','Account does not exist, please verify')/*'账号不存在，请核实'*/);
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
            $identity = $this->getUserInfo();

            if ( (!isset($identity->user) && !Yii::$app->getSecurity()->validatePassword($this->pwd, $identity->password) )
                || (isset($identity->user) &&  !Yii::$app->getSecurity()->validatePassword($this->pwd, $identity->user->password))) {
                $this->addError($attribute,  Yii::t('app/models/LoginForm','Wrong password')/*'密码错误。'*/);
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if($this->validate(['country_code','username','pwd','code'])){
            $identity = $this->getUserInfo();

            if(isset($identity->user))
            {
                $identity = $identity->user;
            }
            return Yii::$app->user->login($identity);
        }
        return false;

    }

    /**
     * 用户登录记录ip和时间
     */
    public function recordIp()
    {
        $userInfo = $this->getUserInfo();
        isset($userInfo->user) && $userInfo = $userInfo->user;
        $user = User::findOne($userInfo->id);
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
                $flag == 1 &&  $message =Yii::t('app/models/LoginForm','The user has been frozen for 30 minutes');//'该用户已被冻结30分钟';
                $flag == 2 &&  $message =Yii::t('app/models/LoginForm','The user has been frozen for 24 hours');//'该用户已被冻结24小时';
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
            $this->addError('username', Yii::t('app/models/LoginForm','The user will miss the account twice and will be frozen for thirty minutes')/*'用户再错两次账号将被冻结三十分钟'*/);
        }
        if($flag == 1){
            $this->addError('username', Yii::t('app/models/LoginForm','The user has been frozen for 30 minutes and 30 minutes after the error will freeze for 24 hours')/*'用户已被冻结30分钟，30分钟后再错将冻结24小时'*/);
        }
        if($flag == 2){
            $this->addError('username', Yii::t('app/models/LoginForm','The user has been frozen for 24 hours') /*'用户已被冻结24小时'*/);
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
                            return ['lock_type' => Yii::t('app/models/LoginForm','Account number')/*'账号'*/,'unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
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
                            return ['lock_type' => Yii::t('app/models/LoginForm','Account number')/*'账号'*/,'unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
                        }
                    }

                }

                //验证码错误
                if($v['status'] == 3){
                    ++ $countCode;
                    if($countCode==3){
                        $unlockAdTime = strtotime('+1 year');
                        if($_SERVER['REQUEST_TIME'] < $unlockAdTime){
                            return ['lock_type' => Yii::t('app/models/LoginForm','Account number')/*'账号'*/,'unlock_time' => date('Y-m-d H:i:s',$unlockAdTime)];
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
            $accounts = User::find()->select(['id','email'])->indexBy('email')->column();
            foreach ($accounts as $account => $id){
                $this->username == Yii::$app->security->decryptByKey(base64_decode($account),Yii::$app->params['inputKey'])
                && $this->identity = User::findOne($id);
            }
        }
        return $this->identity;
    }

    private function getUserInfo()
    {



        if( empty($this->identity))
        {
            $this->identity =  User::findOne(['country_code'=>$this->country_code,'phone_number'=>$this->username]);
        }
        if( empty($this->identity))
        {
            $tmp= UserPhone::findOne(['phone_country_code'=>$this->country_code,'user_phone_number'=>$this->username]);
            if(!empty($tmp))
            {
                $this->identity =    User::findOne(['id'=>$tmp['user_id']]);
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

    public static function checkLearn(){
        $user = User::findOne(Yii::$app->user->id);
        $arr= [];
        if($user->step == 0) {
            if(empty($user->email)){
                $tmp = [ 'type'=>'step-email','url' => '/home/user/bind-email', 'message' => Yii::t('app/index', 'Please edit : email')];
                Yii::$app->getSession()->setFlash('step-email',json_encode($tmp));
                $arr[] = $tmp;
            }

            if(empty($user->username)){
                $tmp = [ 'type'=>'step-username','url' => '/home/user/bind-username', 'message' => Yii::t('app/index', 'Please edit : username')];
                Yii::$app->getSession()->setFlash('step-username',json_encode($tmp));
                $arr[] = $tmp;
            }

            if (empty($user->phone_number)) {
                $tmp = [ 'type'=>'step-phone','url' => '/home/user/set-phone-number', 'message' => Yii::t('app/index', 'Please edit : contact number')];
                Yii::$app->getSession()->setFlash('step-phone',json_encode($tmp));
                $arr[] = $tmp;
            }


            if (empty($user->potato_number)) {
                $tmp = ['type'=>'step-potato','url' => '/home/potato/bind-potato','message'=>Yii::t('app/index','Please bind the communication app : potato')];
                Yii::$app->getSession()->setFlash('step-potato',json_encode($tmp));
                $arr[] = $tmp;
            }
            if (empty($user->telegram_number)) {
                $tmp = ['type'=>'step-telegram', 'url' => '/home/telegram/bind-telegram','message'=>Yii::t('app/index','Please bind the communication app : tetegram')];
                Yii::$app->getSession()->setFlash('step-telegram',json_encode($tmp));
                $arr[] = $tmp;
            }
            $_userGentContact = UserGentContact::findOne(['user_id'=>$user->id]);
            if(empty($_userGentContact))
            {
                $tmp = ['type'=>'step-gent-contact', 'url' => '/home/user/add-urgent-contact-person','message'=>Yii::t('app/index','Please add an emergency contact')];
                Yii::$app->getSession()->setFlash('step-gent-contact',json_encode($tmp));
                $arr[] = $tmp;
            }
            $user->step = 1;
            $user->save();
        }
        return $arr;

    }

    public static function clearFlash()
    {
        Yii::$app->getSession()->hasFlash('step-potato') && Yii::$app->getSession()->removeFlash('step-potato');
        Yii::$app->getSession()->hasFlash('step-telegram') && Yii::$app->getSession()->removeFlash('step-telegram');
        Yii::$app->getSession()->hasFlash('step-email') && Yii::$app->getSession()->removeFlash('step-email');
        Yii::$app->getSession()->hasFlash('step-username') && Yii::$app->getSession()->removeFlash('step-username');
        Yii::$app->getSession()->hasFlash('step-phone') && Yii::$app->getSession()->removeFlash('step-phone');
        Yii::$app->getSession()->hasFlash('step-gent-contact') && Yii::$app->getSession()->removeFlash('step-gent-contact');

    }

    public static function checkFlash()
    {
        $res = Yii::$app->getSession()->getFlash('step-email');
        if(!empty($res)){
            return json_decode($res,true);
        }

        $res = Yii::$app->getSession()->getFlash('step-username');
        if(!empty($res)){
            return json_decode($res,true);
        }

        $res = Yii::$app->getSession()->getFlash('step-phone');
        if(!empty($res)){
            return json_decode($res,true);
        }

        $res = Yii::$app->getSession()->getFlash('step-potato');
        if(!empty($res)){
            return json_decode($res,true);
        }

        $res = Yii::$app->getSession()->getFlash('step-telegram');
        if(!empty($res)){
            return json_decode($res,true);
        }
        $res = Yii::$app->getSession()->getFlash('step-gent-contact');
        if(!empty($res)){
            return json_decode($res,true);
        }
        return [];

    }
}
