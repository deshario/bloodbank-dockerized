<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BranchRequests;

/**
 * BranchRequestsSearch represents the model behind the search form of `app\models\BranchRequests`.
 */
class BranchRequestsSearch extends BranchRequests
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['req_id', 'branch_id', 'blood_group', 'blood_amount', 'paid_amount', 'status'], 'integer'],
            [['created'], 'safe'],
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
        $query = BranchRequests::find();

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
            'req_id' => $this->req_id,
            'branch_id' => $this->branch_id,
            'blood_group' => $this->blood_group,
            'blood_amount' => $this->blood_amount,
            'paid_amount' => $this->paid_amount,
            'created' => $this->created,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
