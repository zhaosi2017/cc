<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\User;

/**
 * UserSearch represents the model behind the search form about `app\modules\admin\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'un_call_number', 'un_call_by_same_number', 'long_time', 'country_code', 'urgent_contact_one_country_code', 'urgent_contact_number_two', 'urgent_contact_two_country_code', 'telegram_country_code', 'potato_country_code', 'reg_time', 'role_id'], 'integer'],
            [['auth_key', 'password', 'account', 'nickname', 'phone_number', 'urgent_contact_number_one', 'urgent_contact_person_one', 'urgent_contact_person_two', 'telegram_number', 'potato_number'], 'safe'],
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
            'urgent_contact_one_country_code' => $this->urgent_contact_one_country_code,
            'urgent_contact_number_two' => $this->urgent_contact_number_two,
            'urgent_contact_two_country_code' => $this->urgent_contact_two_country_code,
            'telegram_country_code' => $this->telegram_country_code,
            'potato_country_code' => $this->potato_country_code,
            'reg_time' => $this->reg_time,
            'role_id' => $this->role_id,
        ]);

        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'urgent_contact_number_one', $this->urgent_contact_number_one])
            ->andFilterWhere(['like', 'urgent_contact_person_one', $this->urgent_contact_person_one])
            ->andFilterWhere(['like', 'urgent_contact_person_two', $this->urgent_contact_person_two])
            ->andFilterWhere(['like', 'telegram_number', $this->telegram_number])
            ->andFilterWhere(['like', 'potato_number', $this->potato_number]);

        return $dataProvider;
    }
}
