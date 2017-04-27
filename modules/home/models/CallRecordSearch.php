<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\home\models\CallRecord;

/**
 * CallRecordSearch represents the model behind the search form about `app\modules\home\models\CallRecord`.
 */
class CallRecordSearch extends CallRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active_call_uid', 'unactive_call_uid', 'call_by_same_times', 'type', 'status', 'call_time'], 'integer'],
            [['contact_number'], 'safe'],
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
        $query = CallRecord::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active_call_uid' => $this->active_call_uid,
            'unactive_call_uid' => $this->unactive_call_uid,
            'call_by_same_times' => $this->call_by_same_times,
            'type' => $this->type,
            'status' => $this->status,
            'call_time' => $this->call_time,
        ]);

        $query->andFilterWhere(['like', 'contact_number', $this->contact_number]);

        return $dataProvider;
    }
}
