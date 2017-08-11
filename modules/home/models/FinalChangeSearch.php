<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\home\models\CallRecord;

/**
 * CallRecordSearch represents the model behind the search form about `app\modules\home\models\CallRecord`.
 */
class FinalChangeSearch extends FinalChangeLog
{
    public $call_time_start;
    public $call_time_end;



    public function attributeLabels()
    {
        $parent = parent::attributeLabels();
        $self = [
            'call_time_start'=>'呼叫起止时间',
            'call_time_end'=>'呼叫截止时间',
        ];
        return array_merge($parent,$self);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        $query = self::find()->where(['user_id' =>  Yii::$app->user->id,
                                       'change_type'=>$this->change_type
        ])->orderBy('call_time desc');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=> 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'change_type' => $this->change_type,
            'amount' => $this->amount,
            'time' => $this->time,
            'user_id' => $this->user_id,
            'comment' => $this->comment,
            'before' => $this->before,
            'after' =>$this->after
        ]);
        if(empty($this->call_time_start)){
            $this->call_time_start = date('Y-m-d' , time());
        }
        if(empty($this->call_time_end)){
            $this->call_time_end = date('Y-m-d' , time() + 24*60*60);
        }
        $query->andFilterWhere(['between', 'time', strtotime($this->call_time_start), strtotime($this->call_time_end)]);


        return $dataProvider;
    }
}
