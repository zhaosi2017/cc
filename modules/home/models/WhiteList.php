<?php

namespace app\modules\home\models;

use Yii;

/*
* @property integer $id
* @property integer $uid
* @property integer $white_uid

 */
class WhiteList extends \app\models\CActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'white_list';
    }

    public function rules()
    {
    	return [
    		[['uid','white_uid'],'integer'],
    	];
    	
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/models/whitelist','Number'),
        ];
    }

   
    public function getUser()
    {
    	return $this->hasOne(User::className(),['id' => 'uid']);
    }

    public function getWhite()
    {
    	return $this->hasOne(User::className(),['id' => 'white_uid']);
    }


    public function beforeSave($insert)
    {
 		if(parent::beforeSave($insert)){
    		$res = WhiteList::find()->where(['uid'=>$this->uid,'white_uid'=>$this->white_uid])->one();
    		if(empty($res))
    		{
    			return true;
    		}
    	}
    	return false;
    }



}