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
use  yii\data\ActiveDataProvider;
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

class CallNumber extends CActiveRecord{

    const NUMBER_STATUS_ON  = 1; //可以使用
    const NUMBER_STATUS_OFF = 2; //不能使用


    const NUMBER_RENT_STATUS_ON  = 1; //可外租
    const NUMBER_RENT_STATUS_OFF = 0; //不可可外租

    public static $numStatusArr =[
        self::NUMBER_STATUS_ON =>'可用',
        self::NUMBER_STATUS_OFF =>'不可用',
    ];

    public static $numRentStatusArr = [
        self::NUMBER_RENT_STATUS_ON  => '可外租',
        self::NUMBER_RENT_STATUS_OFF => '不可外租'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_number';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'number', 'status' , 'time' , 'end_time', 'begin_time' ,'rent_status'], 'integer'],
            [['comment'] , 'string']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '电话号码',
            'status' => '可使用状态',
            'time' => '录入时间',
            'end_time' => '结束时间',
            'begin_time' => '起始时间',
            'rent_status'=>'可外租状态',
            'comment'=>'介绍',
            'price'=>'出租价／天',
            'interface'=>'接口'
        ];
    }


    public function search($params){

        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=> 10,
            ],
        ]);
        return $dataProvider;
    }







}