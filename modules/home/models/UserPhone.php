<?php

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
            'user_id' => '用户id',
            'user_phone_sort' => '号码排序号',
            'reg_time' => '绑定时间',
            'update_time' => '更新时间',
            'phone_country_code' => '号码的国际编码',
            'user_phone_number' => '电话号码'
        ];
    }





}
