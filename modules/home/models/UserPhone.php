<?php

namespace app\modules\home\models;

use Yii;
use app\models\CActiveRecord;
use yii\web\IdentityInterface;
use app\modules\home\models\User;

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
class UserPhone extends CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_phone';
    }


    /**
     * @return int 获取当前的电话的排序号
     */
    public function getPhoneSort(){
        return $this->user_phone_sort;
    }



    /**
     * @return string 获取电话号码
     */
    public function getPhoneNumber()
    {
        return $this->user_phone_number;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'user_phone_sort' , 'reg_time' , 'update_time'], 'integer'],

            [[
                'phone_country_code',
                'user_phone_number',
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
            'user_id' => Yii::t('app/models/user-phone','User id'),
            'user_phone_sort' => Yii::t('app/models/user-phone','Phone sort'),
            'reg_time' => Yii::t('app/models/user-phone','Binding time'),
            'update_time' => Yii::t('app/models/user-phone','Update time'),
            'phone_country_code' => Yii::t('app/models/user-phone','Country code'),
            'user_phone_number' => Yii::t('app/models/user-phone','phone number'),
        ];
    }

    /**
     * @param bool $insert
     * 更新之前 维护一下排序
     * @return boolean
     */
    public function beforeSave($insert)
    {
        $numbers = self::find()->where(array('user_id' => $this->user_id))->orderBy('user_phone_sort desc ')->All();
        if(parent::beforeSave($insert)){
            if($this->isNewRecord) {       //insert
                if (empty($numbers)) {
                    $this->user_phone_sort = 1;
                    if(!$this->updateUserPhoneNumber()) return false;
                } else {
                    foreach ($numbers as $number){
                        if($number->user_phone_number == $this->user_phone_number){ //相同号码不能插入
                            return false;
                        }
                    }
                    $this->user_phone_sort = ++$numbers[0]->user_phone_sort;
                }
                $this->reg_time = $_SERVER['REQUEST_TIME'];
                $this->update_time = $_SERVER['REQUEST_TIME'];
            }else{
                foreach ($numbers as $number){
                    if($number->user_phone_number == $this->user_phone_number){    //相同号码不能插入
                        return false;
                    }
                }
                $this->update_time = $_SERVER['REQUEST_TIME'];
            }
            return true;
        }
    }

    private function updateUserPhoneNumber(){
        $row = User::findOne(array($this->user_id));
        $res = User::findOne(['phone_number'=>$this->user_phone_number]);

        if(!empty($res) && $res['id'] != $this->user_id){
            return true;
        }
        if(empty($row)){
            return false;
        }
        $row->phone_number = $this->user_phone_number;
        $row->country_code = $this->phone_country_code;
        return $row->save();
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }



    /**
     * @return bool
     * 至少保留一个电话号码
     */
    public function beforeDelete()
    {
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
        $numbers = self::findAll(array('user_id'=>Yii::$app->user->id));
        if(count($numbers) > 1){
            return true;
        }else{
            return false;
        }
    }


}
