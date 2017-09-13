<?php

namespace app\modules\home\models;

use yii\base\Model;
use Yii;

/**
 *
 * @property User|null $user This property is read-only.
 *
 */
class ContactForm extends Model
{
    /**
     * 短信限制时间
     */
    const SMS_LIMIT_TIME = 60;
    /**
     * 短信在限制时间内最多发送次数
     */
    const SMS_SEND_NUM = 1;
    /**
     * 短信验证码位数
     */
    const SMS_LENGTH = 4;

    public $country_code;

    public $potato_country_code;

    public $telegram_country_code;

    public $phone_number;

    public $potato_number;

    public $telegram_number;

    public $code;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'phone_number',
                    'potato_number',
                    'telegram_number',
                    'country_code',
                    'potato_country_code',
                    'telegram_country_code',
                ],
                'required'
            ],
            [[
                'country_code',
                'potato_country_code',
                'telegram_country_code',
                'phone_number',
                'potato_number',
                'telegram_number',
            ], 'number'],
            [[
                'country_code',
                'potato_country_code',
                'telegram_country_code',
            ], 'default', 'value'=>''],
            ['code','required','on'=>['phone','telegram','potato']],
            ['nickname','string','length'=>[2, 6], 'message'=>Yii::t('app/models/ContactForm' , 'Please set 2 to 6 Chinese characters for nickname')/*'昵称请设置2～6个汉字'*/],
            ['phone_number','checkPhone','on'=>['phone']],
            ['country_code','match', 'pattern' => '/(^[0-9])+/', 'message' => '国码必须为整数','on'=>['phone']],

        ];

    }

    public function scenarios()
    {
        $parent_scenarios = parent::scenarios();
        $self = [
            'phone' => ['code','country_code','phone_number'],
            'telegram' => ['code','telegram_country_code','telegram_number'],
            'potato' => ['code','potato_country_code','potato_number'],
        ];
        return array_merge($parent_scenarios,$self);
    }

    public function checkPhone($attribute)
    {
        $res = UserPhone::findOne(['user_phone_number'=>$this->phone_number,'user_id'=>Yii::$app->user->id]);

        if(!empty($res))
        {
            $this->addError('phone_number',Yii::t('app/models/ContactForm' , 'The phone already exists'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app/models/ContactForm' ,'Verification code'),//'验证码',
            'country_code' => Yii::t('app/models/ContactForm' ,'Country code'),//'国码',
            'potato_country_code' => Yii::t('app/models/ContactForm' ,'Country code'),
            'telegram_country_code' => Yii::t('app/models/ContactForm' ,'Country code'),
            'phone_number' => Yii::t('app/models/ContactForm' ,'Bind the phone'),//'绑定电话',
            'potato_number' => Yii::t('app/models/ContactForm' ,'Potato number'),//'potato号码',
            'telegram_number' => Yii::t('app/models/ContactForm' ,'Telegram number'),//'telegram号码',
        ];
    }

    public function findModel($id)
    {
        $user = User::findOne($id);
        $this->country_code = $user->country_code;
        $this->potato_country_code = $user->potato_country_code;
        $this->telegram_country_code = $user->telegram_country_code;
        $this->phone_number = $user->phone_number;
        $this->potato_number = $user->potato_number;
        $this->telegram_number = $user->telegram_number;
        return $this;
    }

    /**
     * 短信验证 
     */
    public static function validateSms($type, $code, $phone_number)
    {
        
        $session = Yii::$app->session;
        $verifyCode = $session[$type];
        if(!empty($session['phone_number']) && $phone_number != $session['phone_number'])
        {
            return 1;
        }
        if(empty($code) ||  empty($verifyCode) || $verifyCode != strtolower($code))
        {
            return 1;
        }
        $session->remove($type);
        $session->remove('phone_number');

        return ;
    }


    public static function makeCode()
    {

        $letters = 'bcdfghjklmnpqrstvwxyz';
        $vowels = 'aeiou';
        $code = '';
        for ($i = 0; $i < self::SMS_LENGTH ; ++$i) {
            if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0, 10) > 9) {
                $code .= $vowels[mt_rand(0, 4)];
            } else {
                $code .= $letters[mt_rand(0, 20)];
            }
        }
        return $code;
    }

    public static function makeVerifyCode()
    {
       return rand(1000,9999);
    }

    /**
     * 短信速率限制
     */
    public static function smsRateLimit($type)
    {   

        $time = time()-self::SMS_LIMIT_TIME; 
        $session = Yii::$app->session;
        $name = 'rateLimit'.$type;
        $smsRateLimit = $session[$name];

        if($smsRateLimit['count'] <= 0 &&  $smsRateLimit['time'] > $time)
        {
            return ['messages'=>['status'=>1,'message'=>Yii::t('app/models/ContactForm' ,'Hello! Send text messages can not be too ordinary, please rest!')/*'您好！发送短信不能太平凡,请休息哈！'*/]];
        }else{
            Yii::$app->session[$name] = ['time'=>time(),'count'=>self::SMS_SEND_NUM - 1 ];
        }
        return [];
    }
}
