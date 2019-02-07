<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DonationDayReservation;

/**
 * DonationDayReservationSearch represents the model behind the search form of `app\models\DonationDayReservation`.
 */
class DonationDayReservationSearch extends DonationDayReservation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reserved_id', 'user_id', 'branch_id'], 'integer'],
            [['user_notes', 'reservation_key', 'reserved_date'], 'safe'],
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
        $query = DonationDayReservation::find();

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
            'reserved_id' => $this->reserved_id,
            'user_id' => $this->user_id,
            'branch_id' => $this->branch_id,
            'reserved_date' => $this->reserved_date,
        ]);

        $query->andFilterWhere(['like', 'user_notes', $this->user_notes])
            ->andFilterWhere(['like', 'reservation_key', $this->reservation_key]);

        return $dataProvider;
    }
}
