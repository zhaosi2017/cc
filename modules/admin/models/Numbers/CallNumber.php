<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/16
 * Time: 上午10:04
 */
namespace app\modules\admin\models\Numbers;
use yii\data\ActiveDataProvider;

class CallNumber extends  \app\modules\home\models\CallNumber{
    public $start_time;
    public $close_time;
    public $search_keywords;
        public function search($params){

            if(!empty($params)){
                $this->search_keywords = $params['CallNumber']['search_keywords'];
                $this->start_time = $params['CallNumber']['start_time'];
                $this->close_time = $params['CallNumber']['close_time'];
            }
            $query = self::find();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination'=>[
                    'pagesize'=> 10,
                ],
            ]);


            if(!empty($this->search_keywords)){

                $query->andFilterWhere(['number'=> $this->search_keywords]);
            }

            if(empty($this->start_time)){
                $this->start_time = date('Y-m-d 00:00:00');
            }
            if(empty($this->close_time)){
                $this->close_time = date('Y-m-d 23:59:59');
            }

            $query->andFilterWhere([
                'between' ,
                'time' ,
                strtotime($this->start_time),
                strtotime($this->close_time)
            ]);
            return $dataProvider;
        }

        public function rules()
        {

            return [
                [['comment','end_time', 'begin_time'] , 'string']
            ];
        }


        public function beforeSave($insert)
        {
            if($this->isNewRecord){
                $this->time = time();
            }
            return parent::beforeSave($insert);
        }


}