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
    public $number;
    public $orderSort;

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

    public function attributeLabels()
    {
        $parent = parent::attributeLabels();
        $self = [
            'number'=>'电话号码',

        ];
        return array_merge($parent,$self);
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
        $userId = (int)Yii::$app->user->id;
        $query = self::find()->where(['user_id'=>$userId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=> 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);

        $this->number = isset($params['UserNumberSearch']['number']) ? $params['UserNumberSearch']['number'] :'';
        $this->number && $query->andFilterWhere(['in','user_number.number_id',$this->searchIds($this->number)]);
        return $dataProvider;
    }

    public function searchIds($searchWords,$field = 'number')
    {

        $ids = [0];
        $query = CallNumber::find()->select([$field,'id'])->all();
        foreach ($query as $row)
        {
            $pos = strpos($row[$field],$searchWords);
            if(is_int($pos)){
                $ids[] = $row['id'];
            }
        }

        return $ids;

    }


}
