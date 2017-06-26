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
            ], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'contact_sort' => '联系人排序号',
            'reg_time' => '绑定时间',
            'update_time' => '更新时间',
            'contact_country_code' => '号码的国际编码',
            'contact_phone_number' => '电话号码',
            'contact_nickname'=>   '联系人昵称'
        ];
    }


  public function beforeSave($insert)
  {
     $contacts = self::find()->where(array('uer_id'=>Yii::$app->user->id))->orderBy(' contact_sort desc');

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

        }
        return true;
    }
    return false;
  }


}
