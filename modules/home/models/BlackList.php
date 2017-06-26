<?php

namespace app\modules\home\models;

use Yii;

/*
* @property integer $id
* @property integer $uid
* @property integer $black_uid

 */
class BlackList extends \app\models\CActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'black_list';
    }

    public function rules()
    {
        return [
            [['uid','black_uid'],'integer'],
        ];

    }

    public function attributeLabels()
    {
        return [
            'id' => '编号',
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'uid']);
    }

    public function getBlack()
    {
        return $this->hasOne(User::className(),['id' => 'black_uid']);
    }


    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $res = BlackList::find()->where(['uid'=>$this->uid,'black_uid'=>$this->black_uid])->one();
            if(empty($res))
            {
                return true;
            }
        }
        return false;
    }



}