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
    public $start_time;
    public $end_time;



    public function attributeLabels()
    {
        $parent = parent::attributeLabels();
        $self = [
            'start_time'=>'呼叫起止时间',
            'end_time'=>'呼叫截止时间',
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
        ])->orderBy('time desc');

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
        if(empty($this->start_time)){
            $this->start_time = date('Y-m-d' , time());
        }
        if(empty($this->end_time)){
            $this->end_time = date('Y-m-d' , time() + 24*60*60);
        }
        $query->andFilterWhere(['between', 'time', strtotime($this->start_time), strtotime($this->end_time)]);


        return $dataProvider;
    }


    public function ApiSearch($params)
    {
        $condition = [];
        if(Yii::$app->user->id)
        {
            $condition = ['user_id' =>  Yii::$app->user->id];
        }
        if(isset($params['change_type']) && array_key_exists($params['change_type'],self::$final_change_type))
        {
            $condition['change_type'] = $params['change_type'];
        }

        $model = self::find()->where($condition);


        return $model;
    }
}
