<?php

namespace app\modules\home\models;

use Yii;
use app\models\CActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $password
 * @property string $account
 * @property string $nickname
 * @property integer $un_call_number
 * @property integer $un_call_by_same_number
 * @property integer $long_time
 * @property integer $country_code
 * @property string $phone_number
 * @property string $urgent_contact_number_one
 * @property integer $urgent_contact_one_country_code
 * @property integer $urgent_contact_two_country_code
 * @property integer $urgent_contact_number_two
 * @property string $urgent_contact_person_one
 * @property string $urgent_contact_person_two
 * @property string $telegram_number
 * @property string $potato_number
 * @property integer $telegram_country_code
 * @property integer $telegram_user_id
 * @property integer $potato_user_id
 * @property integer $potato_country_code
 * @property integer $reg_time
 * @property string $reg_ip
 * @property integer $role_id
 * @property integer $status
 */
class User extends CActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return Yii::$app->security->decryptByKey(base64_decode($this->account),Yii::$app->params['inputKey']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'nickname', 'urgent_contact_person_one', 'urgent_contact_person_two', 'reg_ip'], 'string'],

            [[
                'un_call_number',
                'un_call_by_same_number',
                'long_time',
                'reg_time',
                'status',
                'role_id',
                'country_code',
                'telegram_country_code',
                'telegram_user_id',
                'potato_country_code',
                'potato_user_id',
                'urgent_contact_one_country_code',
                'urgent_contact_two_country_code',
            ], 'integer'],

            [['phone_number','urgent_contact_number_one','urgent_contact_number_two', 'telegram_number', 'potato_number'], 'number', 'max' => 99999999999],
            [['auth_key','password'], 'string', 'max' => 64],
            ['nickname' ,'checkName'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'account' => 'Account',
            'nickname' => '昵称',
            'un_call_number' => '被叫总次数',
            'un_call_by_same_number' => '被同一人呼叫次数',
            'long_time' => '时间设置',
            'country_code' => '国码',
            'phone_number' => '绑定电话',
            'urgent_contact_number_one' => '紧急联系电话一',
            'urgent_contact_number_two' => '紧急联系电话二',
            'urgent_contact_person_one' => '紧急联系人一',
            'urgent_contact_person_two' => '紧急联系人二',
            'telegram_number' => 'Telegram Number',
            'potato_number' => 'Potato Number',
            'telegram_country_code' => 'telegram country code',
            'potato_country_code' => 'Potato country code',
            'reg_time' => 'Reg Time',
            'reg_ip' => 'Reg IP',
            'role_id' => 'Role ID',
        ];
    }

    public function checkName($attribute, $params)
    {
        
        if (!preg_match("/^[\x{4e00}-\x{9fa5}]{2,6}+$/u",$this->nickname))
        {
            $this->addError($attribute,'昵称必须时2到6个中文');
        }
        
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->reg_ip = Yii::$app->request->userIP;
                $this->reg_time = $_SERVER['REQUEST_TIME'];
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->account  = base64_encode(Yii::$app->security->encryptByKey($this->account, Yii::$app->params['inputKey']));
                $this->password && $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                $this->nickname && $this->nickname = base64_encode(Yii::$app->security->encryptByKey($this->nickname, Yii::$app->params['inputKey']));
            }else{
                if(!empty(array_column(Yii::$app->request->post(),'password'))){
                    $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                }
                $this->account = base64_encode(Yii::$app->security->encryptByKey($this->account, Yii::$app->params['inputKey']));
                $this->nickname = base64_encode(Yii::$app->security->encryptByKey($this->nickname, Yii::$app->params['inputKey']));
                $this->urgent_contact_person_one = base64_encode(Yii::$app->security->encryptByKey($this->urgent_contact_person_one, Yii::$app->params['inputKey']));
                $this->urgent_contact_person_two = base64_encode(Yii::$app->security->encryptByKey($this->urgent_contact_person_two, Yii::$app->params['inputKey']));
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->account = Yii::$app->security->decryptByKey(base64_decode($this->account), Yii::$app->params['inputKey']);
        $this->nickname && $this->nickname = Yii::$app->security->decryptByKey(base64_decode($this->nickname), Yii::$app->params['inputKey']);
        $this->urgent_contact_person_one && $this->urgent_contact_person_one = Yii::$app->security->decryptByKey(base64_decode($this->urgent_contact_person_one), Yii::$app->params['inputKey']);
        $this->urgent_contact_person_two && $this->urgent_contact_person_two = Yii::$app->security->decryptByKey(base64_decode($this->urgent_contact_person_two), Yii::$app->params['inputKey']);
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}
