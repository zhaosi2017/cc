<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/16
 * Time: 上午10:01
 */

namespace app\modules\admin\models\Numbers;
use yii\data\ActiveDataProvider;

class UserNumber extends \app\modules\home\models\UserNumber{


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
