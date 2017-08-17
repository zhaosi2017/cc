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
class UserNumberForm extends Model
{



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app/models/LoginForm','E-mail / phone / username'),//'邮箱/电话／用户名',
            'pwd' => Yii::t('app/models/LoginForm','Password'),//'密码',
            'code'     => Yii::t('app/models/LoginForm','Verification code'),//'验证码',
        ];
    }







}
