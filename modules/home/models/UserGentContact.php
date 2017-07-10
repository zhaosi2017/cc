<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/6/26
 * Time: 下午2:24
 */

namespace app\modules\home\models;

use Yii;
use app\models\CActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_phone_sort
 * @property string $phone_country_code
 * @property integer $reg_time
 * @property integer $update_time
 * @property string $user_phone_number
 */


class UserGentContact extends  CActiveRecord{

    /**
     * This is the model class for table "user".
     *
     * @property integer $id
     * @property integer $user_id
     * @property integer $contact_sort
     * @property string  $contact_country_code
     * @property string  $contact_nickname
     * @property integer $reg_time
     * @property integer $update_time
     * @property string  $contact_phone_number
     */

    public static function tableName()
    {
        return 'user_gent_contact';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'contact_sort' , 'reg_time' , 'update_time'], 'integer'],

            [[
                'contact_country_code',
                'contact_phone_number',
                'contact_nickname'
            ], 'string'],
            [[
                'contact_country_code',
                'contact_phone_number',
                'contact_nickname'
            ], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('app/models/user-gent-contact','User id'),
            'contact_sort' => Yii::t('app/models/user-gent-contact','Contact sort number'),
            'reg_time' => Yii::t('app/models/user-gent-contact','Binding time'),
            'update_time' => Yii::t('app/models/user-gent-contact','Update time'),
            'contact_country_code' => Yii::t('app/models/user-gent-contact','Country code'),
            'contact_phone_number' => Yii::t('app/models/user-gent-contact','phone number'),
            'contact_nickname'=>   Yii::t('app/models/user-gent-contact','Contact nickname'),
        ];
    }


  public function beforeSave($insert)
  {
     $contacts = self::find()->where(array('user_id'=>Yii::$app->user->id))->orderBy(' contact_sort desc')->all();

    if( parent::beforeSave($insert)){
        if($this->isNewRecord){                        //insert
            if(!empty($contacts)){
                foreach($contacts as $contact){
                    if($contact->contact_country_code == $this->contact_country_code && $contact->contact_phone_number == $this->contact_phone_number)
                        return false;
                }
                $this->contact_sort = ++$contacts[0]->contact_sort;
            }
            $this->user_id = Yii::$app->user->id;
            $this->reg_time = $_SERVER['REQUEST_TIME'];
            $this->update_time = $_SERVER['REQUEST_TIME'];

        }else{
            $this->update_time = $_SERVER['REQUEST_TIME'];
        }
        return true;
    }
    return false;
  }


}
