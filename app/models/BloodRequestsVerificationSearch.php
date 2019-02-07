<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BloodRequestsVerification;

/**
 * BloodRequestsVerificationSearch represents the model behind the search form of `app\models\BloodRequestsVerification`.
 */
class BloodRequestsVerificationSearch extends BloodRequestsVerification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donate_id', 'request_id', 'donated_by', 'donated_to', 'manager_id', 'verified'], 'integer'],
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
        $query = BloodRequestsVerification::find();

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
            'request_id' => $this->request_id,
            'donated_by' => $this->donated_by,
            'donated_to' => $this->donated_to,
            'manager_id' => $this->manager_id,
            'verified' => $this->verified,
            'donated_date' => $this->donated_date,
        ]);

        return $dataProvider;
    }
}
