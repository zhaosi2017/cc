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
            ['phone','checkPhone','on'=>'update-phone'],

        ];
    }

    public function scenarios()
    {
        $parent_scenarios = parent::scenarios();
        $self =[
            'find-password'=> ['phone','country_code','code'],
            'register'=>['phone','country_code','code','password','rePassword'],
            'update-password'=>['password','rePassword','phone','country_code'],
            'update-phone'=>['phone','country_code','code'],

        ];
        return array_merge($parent_scenarios,$self);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('app/models/phone-register-form','Phone'),
            'password' => Yii::t('app/models/phone-register-form','New password'),
            'rePassword' => Yii::t('app/models/phone-register-form','Repeat password'),
            'code'     => Yii::t('app/models/phone-register-form','Verification code'),
            'country_code'=>Yii::t('app/models/phone-register-form','Country code'),
        ];
    }


    public function checkPhone($attribute)
    {
        $res =  User::findOne(['phone_number'=>$this->phone]);
        if( !empty($res) && $res->id != Yii::$app->user->id){
            $this->addError('phone',Yii::t('app/models/phone-register-form','The phone number already exists'));
        }
    }


    public function validateExist($attribute)
    {
        $user = User::findOne(['phone_number'=>$this->phone]);
        if(!empty($user))
        {
            $this->addError($attribute, Yii::t('app/models/phone-register-form','This phone number is already occupied'));
            return ;
        }
        $userPhone = UserPhone::findOne(['user_phone_number'=>$this->phone]);


        if(!empty($userPhone)){
            $this->addError($attribute, Yii::t('app/models/phone-register-form','This phone number is already occupied'));
        }
    }





    public function register()
    {
        $session = Yii::$app->session;
        $user = new User();
        $user->password = $this->password;
        $user->login_time = time();
        $user->login_ip = Yii::$app->request->getUserIP();
        $user->language = $session['language'] ? $session['language'] :'zh-CN';
        $transaction = Yii::$app->db->beginTransaction();
        if($user->insert()){
            $userPhone = new UserPhone();
            $userPhone->user_id = $user->id;
            $userPhone->phone_country_code = $this->country_code;
            $userPhone->user_phone_number = $this->phone;
            $status = $userPhone->save();
            if($status){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
            return $status;
        }else{
            $transaction->rollBack();
            return false;
        }
    }


    public function validatePhone($attribute)
    {
        $res = User::find()->where(['phone_number'=>$this->phone])->one();
        if(empty($res)) {
            $this->addError('phone', Yii::t('app/models/phone-register-form','Phone number does not exist'));
        }

    }

    public function updatePassword()
    {
        $user = User::findOne(['phone_number'=>$this->phone]);
        if(isset($user) && !empty($user)){
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
