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

    public $urgent_contact_person_one;

    public $urgent_contact_person_two;

    public $urgent_contact_number_one;

    public $urgent_contact_number_two;

    public $urgent_contact_one_country_code;

    public $urgent_contact_two_country_code;

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
                    'urgent_contact_number_one',
                    'urgent_contact_number_two',
                    'country_code',
                    'potato_country_code',
                    'telegram_country_code',
                    'urgent_contact_one_country_code',
                    'urgent_contact_two_country_code',
                    'urgent_contact_person_one',
                    'urgent_contact_person_two',
                ],
                'required'
            ],
            [[
                'country_code',
                'potato_country_code',
                'telegram_country_code',
                'urgent_contact_one_country_code',
                'urgent_contact_two_country_code',
                'phone_number',
                'potato_number',
                'telegram_number',
                'urgent_contact_number_one',
                'urgent_contact_number_two',
            ], 'number'],
            [[
                'country_code',
                'potato_country_code',
                'telegram_country_code',
                'urgent_contact_one_country_code',
                'urgent_contact_two_country_code',
                'urgent_contact_number_one',
                'urgent_contact_number_two',
            ], 'default', 'value'=>''],
            [['urgent_contact_person_one','urgent_contact_person_two'], 'string'],
            ['code','required','on'=>['phone','telegram','potato']],
            ['nickname','string','length'=>[2, 6], 'message'=>'昵称请设置2～6个汉字']
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => '验证码',
            'country_code' => '国码',
            'potato_country_code' => '国码',
            'telegram_country_code' => '国码',
            'urgent_contact_one_country_code' => '国码',
            'urgent_contact_two_country_code' => '国码',
            'phone_number' => '绑定电话',
            'potato_number' => 'potato号码',
            'telegram_number' => 'telegram号码',
            'urgent_contact_number_one' => '紧急联系人一号码',
            'urgent_contact_number_two' => '紧急联系人二号码',
            'urgent_contact_person_one' => '紧急联系人一',
            'urgent_contact_person_two' => '紧急联系人二',
        ];
    }

    public function findModel($id)
    {
        $user = User::findOne($id);
        $this->country_code = $user->country_code;
        $this->potato_country_code = $user->potato_country_code;
        $this->telegram_country_code = $user->telegram_country_code;
        $this->urgent_contact_one_country_code = $user->urgent_contact_one_country_code;
        $this->urgent_contact_two_country_code = $user->urgent_contact_two_country_code;
        $this->urgent_contact_number_one = $user->urgent_contact_number_one;
        $this->urgent_contact_number_two = $user->urgent_contact_number_two;
        $this->urgent_contact_person_one = $user->urgent_contact_person_one;
        $this->urgent_contact_person_two = $user->urgent_contact_person_two;
        $this->phone_number = $user->phone_number;
        $this->potato_number = $user->potato_number;
        $this->telegram_number = $user->telegram_number;
        return $this;
    }

    /**
     * 短信验证 
     */
    public static function validateSms($type, $code)
    {
        
        $session = Yii::$app->session;
        $verifyCode = $session[$type];
        if(empty($code) ||  empty($verifyCode) || $verifyCode != $code)
        {
            return true;
        }
        $session->remove($type);
        return false;
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
            return ['messages'=>['status'=>1,'message'=>'您好！发送短信不能太平凡,请休息哈！']];
        }else{
            Yii::$app->session[$name] = ['time'=>time(),'count'=>self::SMS_SEND_NUM - 1 ];
        }
        return [];
    }
}
