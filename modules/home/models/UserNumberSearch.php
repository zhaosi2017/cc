<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\home\models\WhiteList;
use app\modules\home\models\User;
use app\modules\home\models\UserNumber;
/**
 * CallRecordSearch represents the model behind the search form about `app\modules\home\models\CallRecord`.
 */
class UserNumberSearch extends UserNumber
{
    public $search_type;
    public $search_keywords;
    public $call_time_start;
    public $call_time_end;

    /**
     * @inheritdoc
     */


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

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
