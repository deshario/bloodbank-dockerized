<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BranchRequestsVerification;

/**
 * BranchRequestsVerificationSearch represents the model behind the search form of `app\models\BranchRequestsVerification`.
 */
class BranchRequestsVerificationSearch extends BranchRequestsVerification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donate_id', 'branch_requests_id', 'donor_id', 'verified'], 'integer'],
            [['donated_date'], 'safe'],
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
        $query = BranchRequestsVerification::find();

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
            'donate_id' => $this->donate_id,
            'branch_requests_id' => $this->branch_requests_id,
            'donor_id' => $this->donor_id,
            'verified' => $this->verified,
            'donated_date' => $this->donated_date,
        ]);

        return $dataProvider;
    }
}
