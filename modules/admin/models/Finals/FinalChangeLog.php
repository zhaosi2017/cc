<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午10:43
 *
 * 帐变记录
 */
namespace app\modules\admin\models\Finals;

use app\models\CActiveRecord;
use yii\data\ActiveDataProvider;
use Yii;


class FinalChangeLog extends \app\modules\home\models\FinalChangeLog {

    public $start_time;
    public $end_time;


    public function search($params){

        if(!empty($params)){
            $this->change_type = $params['FinalChangeLog']['change_type'];
           // $this->user_id = $params['FinalChangeLog']['user_id'];
            $this->start_time = $params['FinalChangeLog']['start_time'];
            $this->end_time = $params['FinalChangeLog']['end_time'];
        }
        if( !key_exists($this->change_type ,self::$final_change_type)){
            $query = self::find();
        }else{
            $query = self::find()->where(['change_type'=>(int)$this->change_type]);
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
        if(empty(!$this->user_id)){
            $query->andFilterWhere(['user_id'=>$this->user_id]);
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