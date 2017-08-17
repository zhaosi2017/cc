<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/4
 * Time: 下午2:48
 */
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
 * @property integer $number_id
 * @property integer $time
 * @property integer $end_time
 * @property integer $begin_time
 */

class UserNumber extends CActiveRecord{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_number';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'number_id' , 'time' , 'end_time', 'begin_time'], 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'number_id' => '号码',
            'time' => '租赁时间',
            'end_time' => '租赁结束时间',
            'begin_time' => '租赁起始时间',
        ];
    }





}