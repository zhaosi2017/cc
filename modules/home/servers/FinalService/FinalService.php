<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/3
 * Time: 下午3:08
 * 资金服务组
 * 处理用户的充值，使用等等和资金相关的业务
 */
namespace app\modules\home\servers\FinalService;

use app\modules\home\models\FinalChangeLog;
use app\modules\home\models\User;
use Yii;
class FinalService{







    /**
     *充值
     */
    public function Recharge($user_id , $amount , $commit ='' ){

        Yii::$app->db->transaction->begin('READ COMMITTED');
        $user_model = User::findOne($user_id);
        if(empty($user_model)){
            Yii::$app->db->transaction->rollBack();
            return false;
        }
        if($amount <= 0){
            Yii::$app->db->transaction->rollBack();
            return true;
        }
        $change = new FinalChangeLog();
        $change->before = $user_model->amount;

        $user_model->amount = $user_model->amount + $amount;
        if(!$user_model->save()){
            Yii::$app->db->transaction->rollBack();
            return false;
        }
        $change->after = $user_model->amount;
        $change->user_id = $user_id;
        $change->amount = $amount;
        $change->comment = $commit;
        $change->change_type = FinalChangeLog::FINAL_CHANGE_TYPE_RECHARGE;
        if(!$change->save()){
            Yii::$app->db->transaction->rollBack();
            return false;
        }
        Yii::$app->db->transaction->commit();
        return true;

    }

    /**
     * @param $user_id
     * @param $amount
     * @param $commit
     * 提供给外部调用 用户使用资金
     */
    public function apply($user_id , $amount , $commit ='' ){

        Yii::$app->db->transaction->begin('READ COMMITTED');
        $user_model = User::findOne($user_id);
        if(empty($user_model)){
            Yii::$app->db->transaction->rollBack();
            return false;
        }
        if($amount <= 0){
            Yii::$app->db->transaction->rollBack();
            return true;
        }

        $change = new FinalChangeLog();
        $change->before = $user_model->amount;

        $user_model->amount = $user_model->amount - $amount;
        if(!$user_model->save()){
            Yii::$app->db->transaction->rollBack();
            return false;
        }

        $change->after = $user_model->amount;
        $change->user_id = $user_id;
        $change->amount = $amount;
        $change->comment = $commit;
        $change->change_type = FinalChangeLog::FINAL_CHANGE_TYPE_CONSUME;
        if(!$change->save()){
            Yii::$app->db->transaction->rollBack();
            return false;
        }
        Yii::$app->db->transaction->commit();
        return true;
    }






}