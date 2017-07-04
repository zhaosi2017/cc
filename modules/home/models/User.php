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
 * @property string $username
 * @property string $nickname
 * @property integer $un_call_number
 * @property integer $un_call_by_same_number
 * @property integer $long_time
 * @property integer $country_code
 * @property string $phone_number
 * @property string $telegram_number
 * @property string $potato_number
 * @property integer $telegram_country_code
 * @property integer $telegram_user_id
 * @property integer $potato_user_id
 * @property integer $potato_country_code
 * @property integer $reg_time
 * @property string $reg_ip
 * @property integer $login_time
 * @property string $login_ip
 * @property integer $role_id
 * @property integer $status
 * @property string $language
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
            [['account', 'nickname','username', 'reg_ip'], 'string'],

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
            ], 'integer'],

            [['phone_number', 'telegram_number', 'potato_number'], 'number','max'=> 9999999999999],
            [['auth_key','password'], 'string', 'max' => 64],
            [['login_ip','login_time'],'safe'],
            ['nickname' ,'checkName'],
            [['un_call_number','un_call_by_same_number','long_time'],'required','on'=>'harassment'],
            ['username','required','on'=>'bind-username'],
            ['username','checkUsername','on'=>'bind-username'],
            [['username'],'string','min'=>6,'max'=>100,'on'=>'bind-username'],
            ['account','checkAccount','on'=>'bind-email'],
            ['account','required','message'=>Yii::t('app/models/user','Email can not be empty'),'on'=>'bind-email'],
            ['account','email','message'=>Yii::t('app/models/user','Email format is incorrect'),'on'=>'bind-email'],

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
            'account' => Yii::t('app/models/user','Email'),
            'username'=>Yii::t('app/models/user','Username'),
            'nickname' => Yii::t('app/models/user','Nickname'),
            'un_call_number' => Yii::t('app/harassment','Total number of times to be called'),
            'un_call_by_same_number' => Yii::t('app/harassment','The number of calls by the same person'),
            'long_time' => Yii::t('app/harassment','Time setting'),
            'country_code' => Yii::t('app/models/user','Country code'),
            'phone_number' => Yii::t('app/models/user','Bind the phone'),
            'telegram_number' => 'Telegram Number',
            'potato_number' => 'Potato Number',
            'telegram_country_code' => 'telegram country code',
            'potato_country_code' => 'Potato country code',
            'reg_time' => 'Reg Time',
            'reg_ip' => 'Reg IP',
            'role_id' => 'Role ID',
            'urgent_contact_one_country_code'=>Yii::t('app/models/user','Country code'),
            'urgent_contact_two_country_code'=>Yii::t('app/models/user','Country code'),
        ];
    }

     public function scenarios()
    {
        $scenarios = parent::scenarios();
        $res = [
            'harassment'=>['un_call_number','un_call_by_same_number','long_time'],
            'bind-username'=>['username'],
            'bind-email'=>['account'],
            'change-language'=>['language'],
        ];
        return array_merge($scenarios,$res);
    }

    public function checkName($attribute, $params)
    {
        
        if (!preg_match("/^[\x{4e00}-\x{9fa5}]{2,6}+$/u",$this->nickname))
        {
            $this->addError($attribute,Yii::t('app/models/user','Please set the correct nickname'));
        }
        
    }

    public function checkUsername($attribute)
    {
        $rows = User::find()->select(['username'])->indexBy('id')->column();
        $accounts = [];
        foreach ($rows as $i => $v)
        {

            if($this->id == $i)
            {
                continue;
            }
            $accounts[] = Yii::$app->security->decryptByKey(base64_decode($v), Yii::$app->params['inputKey']);

        }

        if(in_array($this->username, $accounts)){
            $this->addError($attribute, Yii::t('app/models/user','Account already exists'));
        }
    }


    public function checkAccount ($attribute)
    {
        $rows = User::find()->select(['account'])->indexBy('id')->column();
        $accounts = [];
        foreach ($rows as $i => $v)
        {

            if($this->id == $i)
            {
                continue;
            }
            $accounts[] = Yii::$app->security->decryptByKey(base64_decode($v), Yii::$app->params['inputKey']);

        }

        if(in_array($this->account, $accounts)){
            $this->addError($attribute, Yii::t('app/models/user','The email already exists'));
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
                $this->username  = base64_encode(Yii::$app->security->encryptByKey($this->username, Yii::$app->params['inputKey']));
                $this->password && $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                $this->nickname && $this->nickname = base64_encode(Yii::$app->security->encryptByKey($this->nickname, Yii::$app->params['inputKey']));
            }else{
                if(!empty(array_column(Yii::$app->request->post(),'password'))){    //必须是post中的password 否则出现二次加密
                    $this->password = Yii::$app->getSecurity()->generatePasswordHash(array_column(Yii::$app->request->post(),'password')[0]);
                }
                $this->username = base64_encode(Yii::$app->security->encryptByKey($this->username, Yii::$app->params['inputKey']));
                $this->account = base64_encode(Yii::$app->security->encryptByKey($this->account, Yii::$app->params['inputKey']));
                $this->nickname = base64_encode(Yii::$app->security->encryptByKey($this->nickname, Yii::$app->params['inputKey']));
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
        $this->username && $this->username = Yii::$app->security->decryptByKey(base64_decode($this->username), Yii::$app->params['inputKey']);

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
