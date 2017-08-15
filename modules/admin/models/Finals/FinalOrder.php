<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午10:35
 * 充值订单
 */
namespace app\modules\admin\models\Finals;
use yii\data\ActiveDataProvider;
use Yii;


class FinalOrder extends \app\modules\home\models\FinalOrder {

    public $start_time;
    public $end_time;

        public function search($params){
            if(!empty($params)){
                $this->status = $params['FinalOrder']['status'];
                $this->start_time = $params['FinalOrder']['start_time'];
                $this->end_time = $params['FinalOrder']['end_time'];
            }
            if( !key_exists($this->status ,self::$order_status_map)){
                $query = self::find();
            }else{
                $query = self::find()->where(['status'=>(int)$this->status]);
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination'=>[
                    'pagesize'=> 10,
                ],
            ]);
            if(empty($this->start_time)){
                $this->start_time = date('Y-m-d 00:00:00');
            }
            if(empty($this->end_time)){
                $this->end_time = date('Y-m-d 23:59:59');
            }

            $query->andFilterWhere([
                'between' ,
                'time' ,
                strtotime($this->start_time),
                strtotime($this->end_time)
            ]);
            return $dataProvider;


        }













}