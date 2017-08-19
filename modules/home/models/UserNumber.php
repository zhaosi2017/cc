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
use app\modules\home\servers\FinalService\FinalService;
use yii\db\Transaction;
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
            [['id', 'user_id', 'number_id' , 'time' , 'end_time', 'begin_time','sorting'], 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('app/number/index','User'),
            'number_id' => Yii::t('app/number/index','Phone number'),
            'time' => Yii::t('app/number/index','Purchase time'),
            'end_time' => Yii::t('app/number/index','Rent end time'),
            'begin_time' => Yii::t('app/number/index','Rent start time'),
            'sorting'=>Yii::t('app/number/index','Sorting'),
        ];
    }

    /**
     * @param $arr array
     */
    public function BuyNumber($arr)
    {
        $userid = Yii::$app->user->id ? Yii::$app->user->id:0;
        $user = User::findOne($userid);
        $amount = $arr['amount'];
        if((float)$user->amount < 0 || $amount > (float)$user->amount)
        {
            Yii::$app->session->setFlash('error',Yii::t('app/number/index','Your balance is insufficient'));
            return false;
        }

        Yii::$app->db->beginTransaction(Transaction::READ_COMMITTED);
        $transaction = Yii::$app->db->getTransaction();

        $userNumber = new UserNumber();
        $userNumber->user_id = $userid;
        $userNumber->time = time();
        $userNumber->begin_time = $arr['begin_time'];
        $userNumber->end_time = $arr['end_time'];
        $userNumber->number_id = $arr['number_id'];
        if( !$userNumber->save())
        {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error',Yii::t('app/number/index','Operation failed'));
            return false;
        }


        $finalService = new FinalService();

        $res = $finalService->apply($userid, $amount ,FinalChangeLog::FINAL_CHANGE_TYPE_BUYNUMBER);
        if($res)
        {
            $transaction->commit();
            return true;
        }else{
            $transaction->rollBack();
            Yii::$app->session->setFlash(Yii::t('app/number/index','Operation failed'));
            return false;
        }

    }





}