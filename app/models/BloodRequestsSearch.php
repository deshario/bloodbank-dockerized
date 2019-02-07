<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BloodRequests;

/**
 * BloodRequestsSearch represents the model behind the search form of `app\models\BloodRequests`.
 */
class BloodRequestsSearch extends BloodRequests
{

    public $incoming = 0;
    public $waiting = 1;
    public $closed = 2;
    public $totalSum;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'requester_id','blood_group', 'blood_amount', 'paid_amount', 'status'], 'integer'],
            [['lat_long', 'full_address', 'reason', 'postal_code', 'created', 'req_key'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = BloodRequests::find();

        $revenue = $query->sum('blood_amount');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $this->totalSum = $revenue;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'requester_id' => $this->requester_id,
            'blood_group' => $this->blood_group,
            'blood_amount' => $this->blood_amount,
            'paid_amount' => $this->paid_amount,
            'created' => $this->created,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'lat_long', $this->lat_long])
            ->andFilterWhere(['like', 'full_address', $this->full_address])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'req_key', $this->req_key]);

        return $dataProvider;
    }
}
