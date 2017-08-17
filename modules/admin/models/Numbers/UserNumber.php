<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/16
 * Time: 上午10:01
 */

namespace app\modules\admin\models\Numbers;
use app\modules\admin\models\User;
use yii\data\ActiveDataProvider;

class UserNumber extends \app\modules\home\models\UserNumber{

    public $start_time;
    public $colse_time;
    public $search_keywords;
    public function search($params){

        if(!empty($params)){
            $this->search_keywords = $params['UserNumber']['search_keywords'];
            $this->start_time      = $params['UserNumber']['start_time'];
            $this->colse_time      = $params['UserNumber']['colse_time'];
        }
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=> 10,
            ],
        ]);
        if(!empty($this->search_keywords)){
            $user = User::find()->where(['nickname'=>$this->search_keywords])->one();
            if(empty($user)){
              $user = new User();
                $user->id = 0;
            }
            $query->andFilterWhere(['user_id'=>$user->id]);
        }

        if(empty($this->start_time)){
            $this->start_time = date('Y-m-d 00:00:00');
        }
        if(empty($this->colse_time)){
            $this->colse_time = date('Y-m-d 23:59:59');
        }

        $query->andFilterWhere([
            'between' ,
            'time' ,
            strtotime($this->start_time),
            strtotime($this->colse_time)
        ]);




        return $dataProvider;
    }





}
