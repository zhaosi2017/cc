<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;
use yii\captcha\CaptchaValidator;
use app\modules\home\models\ContactForm;
use app\modules\home\models\User;
use app\modules\home\models\UserPhone;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PhoneRegisterForm extends Model
{

    public $phone;
    public $country_code; //国码
    public $password;
    public $rePassword;
    public $code;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['country_code','phone','code'], 'required'],
            [['rePassword', 'password'], 'string', 'length' => [8,15]],
            ['rePassword', 'compare', 'compareAttribute'=>'password','operator'=>'===','message'=>'两次密码不一致'],
            ['country_code','required'],
            [['country_code'],'number'],
            [['rePassword', 'password'],'required','on'=>'register'],

            ['phone','validatePhone','on'=>'find-password,update-password'],
            ['phone','validateExist','on'=>'register'],
            ['password', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/', 'message'=>'密码格式错误'],
            ['rePassword', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/', 'message'=>'密码格式错误'],
            ['password','required','on'=>'update-password'],
            ['rePassword','required','on'=>'update-password'],

        ];
    }

    public function scenarios()
    {
        $parent_scenarios = parent::scenarios();
        $self =[
            'find-password'=> ['phone','country_code','code'],
            'register'=>['phone','country_code','code','password','rePassword'],
            'update-password'=>['password','rePassword','phone','country_code'],

        ];
        return array_merge($parent_scenarios,$self);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => '电话号码',
            'password' => '新密码',
            'rePassword' => '重复密码输入',
            'code'     => '验证码',
            'country_code'=>'国码',
        ];
    }


    public function validateExist($attribute)
    {
        $rows = UserPhone::find()->select(['user_phone_number'])->indexBy('id')->column();

        $accounts = [];
        foreach ($rows as $i => $v)
        {
            $accounts[] = $v;
        }

        if(in_array($this->phone, $accounts)){
            $this->addError($attribute, '此电话号码已被占用');
        }
    }





    public function register()
    {
        $user = new User();
        $user->password = $this->password;
        $user->login_time = time();
        $user->login_ip = Yii::$app->request->getUserIP();
        if($user->insert()){
            $userPhone = new UserPhone();
            $userPhone->user_id = $user->id;
            $userPhone->phone_country_code = $this->country_code;
            $userPhone->user_phone_number = $this->phone;
            return $userPhone->save();
        }else{

            return false;
        }


    }


    public function validatePhone($attribute)
    {
        $res = UserPhone::find()->where(['phone_country_code'=>$this->country_code,'user_phone_number'=>$this->phone])->one();
        if(empty($res)) {
            $this->addError('phone', '手机号不存在');
        }

    }

    public function updatePassword()
    {
        $userPhone = UserPhone::find()->where(['phone_country_code'=>$this->country_code,'user_phone_number'=>$this->phone])->one();
        if(isset($userPhone->user) && !empty($userPhone->user)){
            $user = $userPhone->user;
            $user->password = $this->password;
            $user->save();
            $this->deleteLoginNum();
            return true;
        }else{
            return false;
        }

    }
    /**
     * 用户修改密码后，删除该用户登录时错误密码的记录数
     */
    private function deleteLoginNum()
    {
        $redis = Yii::$app->redis;
        $key = $this->phone.'-homenum' ;
        if($redis->exists($key))
        {
            $redis->del($key);
        }
    }

}
