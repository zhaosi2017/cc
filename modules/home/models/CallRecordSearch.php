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
            [['id', 'active_call_uid', 'unactive_call_uid', 'call_by_same_times', 'type', 'status'], 'integer'],
            [['contact_number', 'search_type', 'search_keywords', 'active_account', 'call_time_start', 'call_time_end'], 'safe'],
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
        $recordStatus = '1';
        $uid = Yii::$app->user->id;
        $query = CallRecord::find()->where(['record_status' => $recordStatus, 'active_call_uid' => $uid])->orWhere(['record_status' => $recordStatus, 'unactive_call_uid' => $uid])->orderBy('call_time desc');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=> 10,
            ],
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

        if((!empty($this->call_time_start) && !empty($this->call_time_end)) && ($this->call_time_start <= $this->call_time_end)){
            $this->call_time_start = strtotime($this->call_time_start);
            $this->call_time_end = strtotime($this->call_time_end);
            $query->andFilterWhere(['between','call_time', $this->call_time_start, $this->call_time_end]);
        }
        $this->search_type ==1 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like', 'active_account', $this->search_keywords]);
        $this->search_type ==2 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like', 'active_nickname', $this->search_keywords]);
        $this->search_type ==3 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like', 'contact_number', $this->search_keywords]);
        $this->search_type ==4 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like', 'unactive_account', $this->search_keywords]);
        $this->search_type ==5 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like', 'unactive_nickname', $this->search_keywords]);
        $this->search_type ==6 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like', 'unactive_contact_number', $this->search_keywords]);
        $this->search_type ==7 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['in', 'type', $this->search_keywords]);

        return $dataProvider;
    }
}
