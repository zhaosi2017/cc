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