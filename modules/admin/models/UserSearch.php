<?php

namespace app\modules\admin\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `app\modules\admin\models\User`.
 */
class UserSearch extends User
{
    public $start_date;

    public $end_date;

    public $search_type;

    public $search_keywords;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'un_call_number', 'un_call_by_same_number', 'long_time', 'country_code', 'telegram_country_code', 'potato_country_code', 'reg_time', 'role_id'], 'integer'],
            [['auth_key', 'password', 'account', 'nickname', 'phone_number', 'urgent_contact_person_two', 'telegram_number', 'potato_number', 'search_type', 'search_keywords','start_date','end_date'], 'safe'],
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
        $query = User::find();

//        $query->andWhere(['status'=>Yii::$app->requestedAction->id == 'index' ? 0 : 1]);
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
            'un_call_number' => $this->un_call_number,
            'un_call_by_same_number' => $this->un_call_by_same_number,
            'long_time' => $this->long_time,
            'country_code' => $this->country_code,
            'telegram_country_code' => $this->telegram_country_code,
            'potato_country_code' => $this->potato_country_code,
            'reg_time' => $this->reg_time,
            'role_id' => $this->role_id,
        ]);
       
        if(empty($this->start_date)  && !empty($this->end_date))
        {
            $query->andFilterWhere(['<=','user.reg_time', strtotime($this->end_date)+24*60*60]);
        }

        if(!empty($this->start_date)  && empty($this->end_date))
        {
            $query->andFilterWhere(['>=','user.reg_time', strtotime($this->start_date)]);
        }

        if(!empty($this->start_date) && !empty($this->end_date )){
            if($this->start_date > $this->end_date)
            {
                $tmp = $this->end_date;
                $this->end_date = $this->start_date;
                $this->start_date = $tmp;
            }
            $query->andFilterWhere(['between','user.reg_time', strtotime($this->start_date), strtotime($this->end_date)+24*60*60]);
        }

        $this->search_type == 1 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like','user.potato_number', $this->search_keywords]);
        $this->search_type == 2 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like','user.telegram_number', $this->search_keywords]);
        $this->search_type == 3 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['in', 'user.id', $this->searchIds($this->search_keywords)]);
        $this->search_type == 4 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['in', 'user.id', $this->searchIds($this->search_keywords, 'nickname')]);
        $this->search_type == 5 && strlen($this->search_keywords)>0 && $query->andFilterWhere(['like','user.phone_number', $this->search_keywords]);


        return $dataProvider;
    }

    public function searchIds($searchWords, $field='account')
    {
        $ids = [0];
        $query = $this::find()->select([$field,'id'])->all();
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
