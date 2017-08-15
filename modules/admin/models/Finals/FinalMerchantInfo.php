<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午10:45
 */

namespace app\modules\admin\models\Finals;

use yii\data\ActiveDataProvider;
use Yii;


class FinalMerchantInfo extends \app\modules\home\models\FinalMerchantInfo {



        public function search(){

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