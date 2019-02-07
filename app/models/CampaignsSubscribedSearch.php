<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CampaignsSubscribed;

/**
 * CampaignsSubscribedSearch represents the model behind the search form of `app\models\CampaignsSubscribed`.
 */
class CampaignsSubscribedSearch extends CampaignsSubscribed
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subscribe_id', 'subscribed_campaign', 'subscribed_by'], 'integer'],
            [['subscribed_date'], 'safe'],
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
        $query = CampaignsSubscribed::find();

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
            'subscribe_id' => $this->subscribe_id,
            'subscribed_campaign' => $this->subscribed_campaign,
            'subscribed_by' => $this->subscribed_by,
            'subscribed_date' => $this->subscribed_date,
        ]);

        return $dataProvider;
    }
}
