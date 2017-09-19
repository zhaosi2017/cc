<?php

namespace app\modules\home\models\SmsForms;

use yii\base\Model;
use Yii;
use app\modules\home\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SmsForm extends Model
{
    public $number;
    public $type;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['number', 'type'], 'required'],
            ['number','match', 'pattern' => '/(^[0-9])+/', 'message' => Yii::t("app/login","Country code number must be number")],
        ];
    }
}