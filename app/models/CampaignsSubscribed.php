<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "campaigns_subscribed".
 *
 * @property int $subscribe_id
 * @property int $subscribed_campaign Campaign ID
 * @property int $subscribed_by User ID
 * @property string $subscribed_date Subscibed Date
 *
 * @property Campaigns $subscribedCampaign
 * @property Users $subscribedBy
 */
class CampaignsSubscribed extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UNSUBSCRIBE = 'unsubscribe';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaigns_subscribed';
    }

    public function rules()
    {
        return [
            [['subscribed_campaign', 'subscribed_by'], 'required'],
            [['subscribed_campaign', 'subscribed_by'], 'integer'],
            [['subscribed_date'], 'safe'],
            [['subscribed_campaign'], 'exist', 'skipOnError' => true, 'targetClass' => Campaigns::className(), 'targetAttribute' => ['subscribed_campaign' => 'campaign_id']],
            [['subscribed_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['subscribed_by' => 'u_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subscribe_id' => 'Subscribe ID',
            'subscribed_campaign' => 'Subscribed Campaign',
            'subscribed_by' => 'Subscribed By',
            'subscribed_date' => 'Subscribed Date',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['subscribed_by','subscribed_campaign'];
        $scenarios['unsubscribe'] = ['subscribed_by','subscribed_campaign'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscibedCampaign()
    {
        return $this->hasOne(Campaigns::className(), ['campaign_id' => 'subscibed_campaign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubsribedBy()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'subsribed_by']);
    }
}
