<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\home\models\BlackList;
use app\modules\home\models\User;

/**
 * CallRecordSearch represents the model behind the search form about `app\modules\home\models\CallRecord`.
 */
class BlackListSearch extends BlackList
{
    public $search_type;
    public $search_keywords;
    public $call_time_start;
    public $call_time_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'black_uid'], 'integer'],
            [[ 'search_type', 'search_keywords'], 'safe'],
        ];
    }

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

        $uid = Yii::$app->user->id;
        $query = BlackList::find()->where(['uid' => $uid]);
        // add conditions that should always apply here
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'black_uid' => $this->black_uid,
        ]);



        $this->search_type == 1 && !empty($this->search_keywords) && strlen($this->search_keywords)>0 && $query->andFilterWhere(['in', 'black_uid', $this->searchIds($this->search_keywords,'account')]);
        $this->search_type == 2 && !empty($this->search_keywords) && strlen($this->search_keywords)>0 && $query->andFilterWhere(['in', 'black_uid', $this->searchIds($this->search_keywords,'telegram_number')]);
        $this->search_type == 3 && !empty($this->search_keywords) && strlen($this->search_keywords)>0 && $query->andFilterWhere(['in', 'black_uid', $this->searchIds($this->search_keywords,'potato_number')]);

        return $dataProvider;
    }


    public function searchIds($searchWords, $field='account')
    {
        $ids = [0];
        $query = User::find()->select([$field,'id'])->all();
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
