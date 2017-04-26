<?php

namespace app\modules\home\models;

use yii\base\Model;

/**
 *
 * @property User|null $user This property is read-only.
 *
 */
class ContactForm extends Model
{
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
            [['country_code','potato_country_code','telegram_country_code','urgent_contact_one_country_code', 'urgent_contact_two_country_code'], 'default', 'value'=>''],
            [['urgent_contact_person_one','urgent_contact_person_two'], 'string'],
            ['code', 'captcha', 'message'=>'验证码输入不正确', 'captchaAction'=>'/home/user/captcha'],
        ];

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
}
