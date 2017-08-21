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
 * @property integer $status
 * @property integer $number
 * @property integer $time
 * @property integer $end_time
 * @property integer $begin_time
 * @property  integer $rent_status
 * @property  integer $price
 * @property  integer $interface
 */

class CallNumber extends CActiveRecord{

    const NUMBER_STATUS_ON  = 1; //可以使用
    const NUMBER_STATUS_OFF = 2; //不能使用


    const NUMBER_RENT_STATUS_ON  = 1; //可外租
    const NUMBER_RENT_STATUS_OFF = 0; //不可可外租





    public static  function getNumbStatus(){
        return [
            self::NUMBER_STATUS_ON => Yii::t('app/number/index','Available') ,
            self::NUMBER_STATUS_OFF =>Yii::t('app/number/index','Unavailable') ,
            ];
    }
    public static  function getRentStatus(){
        return [
            self::NUMBER_RENT_STATUS_ON  => Yii::t('app/number/index','Can be rented') ,
            self::NUMBER_RENT_STATUS_OFF =>Yii::t('app/number/index','Can not rent') ,
        ];
    }
    public static $numStatusArr =[
        self::NUMBER_STATUS_ON => '可用' ,
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
            'number' => Yii::t('app/number/index','Phone number'),
            'status' => Yii::t('app/number/index','Status'),
            'time' => Yii::t('app/number/index','Create time'),
            'end_time' => Yii::t('app/number/index','Can be used to rent the start time'),
            'begin_time' => Yii::t('app/number/index','Can be used to rent the end time'),
            'rent_status'=>Yii::t('app/number/index','Rent status'),
            'comment'=>Yii::t('app/number/index','Introduction'),
            'price'=>Yii::t('app/number/index','Rental price／day'),
            'interface'=>Yii::t('app/number/index','Interface'),
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
        $this->load($params);
        $this->number && $query->andFilterWhere(['like','number',$this->number]);
        return $dataProvider;
    }


    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->time = time();
        }
        return parent::beforeSave($insert);
    }


}