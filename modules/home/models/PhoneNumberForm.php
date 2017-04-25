<?php

namespace app\modules\home\models;

use yii\base\Model;

/**
 *
 * @property User|null $user This property is read-only.
 *
 */
class PhoneNumberForm extends Model
{
    public $country_code;

    public $phone_number;

    public $code;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['country_code'], 'integer'],
            [['phone_number'], 'string'],
            ['code', 'captcha', 'message'=>'验证码输入不正确', 'captchaAction'=>'/home/user/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => '验证码'
        ];
    }

    public function findModel($id)
    {
        $user = User::findOne($id);
        $this->country_code = $user->country_code;
        $this->phone_number = $user->phone_number;
        return $this;
    }
}
