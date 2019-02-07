<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SavedBloodRequests;

/**
 * SavedBloodRequestsSearch represents the model behind the search form of `app\models\SavedBloodRequests`.
 */
class SavedBloodRequestsSearch extends SavedBloodRequests
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['saved_id', 'request_id', 'saved_by'], 'integer'],
            [['saved_date'], 'safe'],
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
        $query = SavedBloodRequests::find();

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
            'saved_id' => $this->saved_id,
            'request_id' => $this->request_id,
            'saved_by' => $this->saved_by,
            'saved_date' => $this->saved_date,
        ]);

        return $dataProvider;
    }
}
