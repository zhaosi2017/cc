<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午10:46
 *  和充值三方的交互日志
 */
namespace app\modules\admin\models\Finals;

use app\models\CActiveRecord;
use Yii;


class FinalMutualLog extends \app\modules\home\models\FinalMutualLog {

        public function search($params){

            if(!key_exists($params['type'] , self::$final_mutual_type)){
                $type  = 0;
            }else{
                $type  = (int)$params['type'];
            }

            $query = self::find()->where(['type' =>$type ]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination'=>[
                    'pagesize'=> 10,
                ],
            ]);
            $query->andFilterWhere([
                'id' => $this->id,
                'active_call_uid' => $this->active_call_uid,
                'unactive_call_uid' => $this->unactive_call_uid,
                'call_by_same_times' => $this->call_by_same_times,
                'type' => $this->type,
                'status' => $this->status,
                'call_time' => $this->call_time,
            ]);

        }



}