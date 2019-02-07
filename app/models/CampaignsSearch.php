<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Campaigns;

/**
 * CampaignsSearch represents the model behind the search form of `app\models\Campaigns`.
 */
class CampaignsSearch extends Campaigns
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'campaign_creator', 'campaign_status'], 'integer'],
            [['campaign_name', 'campaign_desc', 'campaign_img', 'campaign_created', 'campaign_coordinates', 'campaign_address', 'campaign_key'], 'safe'],
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
        $query = Campaigns::find();

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
            'campaign_id' => $this->campaign_id,
            'campaign_created' => $this->campaign_created,
            'campaign_creator' => $this->campaign_creator,
            'campaign_status' => $this->campaign_status,
        ]);

        $query->andFilterWhere(['like', 'campaign_name', $this->campaign_name])
            ->andFilterWhere(['like', 'campaign_desc', $this->campaign_desc])
            ->andFilterWhere(['like', 'campaign_img', $this->campaign_img])
            ->andFilterWhere(['like', 'campaign_coordinates', $this->campaign_coordinates])
            ->andFilterWhere(['like', 'campaign_address', $this->campaign_address])
            ->andFilterWhere(['like', 'campaign_key', $this->campaign_key]);

        return $dataProvider;
    }
}
