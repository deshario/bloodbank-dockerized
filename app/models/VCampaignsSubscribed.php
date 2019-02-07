<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_campaigns_subscribed".
 *
 * @property int $u_id
 * @property string $username Username
 * @property string $profile_token Firebase Token
 * @property int $campaign_id
 * @property string $campaign_name Campaign Name
 * @property string $campaign_key Campaign Key
 * @property string $subscribed_date Subscibed Date
 */
class VCampaignsSubscribed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_campaigns_subscribed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['u_id', 'campaign_id'], 'integer'],
            [['username', 'campaign_name', 'campaign_key', 'subscribed_date'], 'required'],
            [['profile_token'], 'string'],
            [['subscribed_date'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['campaign_name'], 'string', 'max' => 100],
            [['campaign_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'u_id' => 'U ID',
            'username' => 'Username',
            'profile_token' => 'Profile Token',
            'campaign_id' => 'Campaign ID',
            'campaign_name' => 'Campaign Name',
            'campaign_key' => 'Campaign Key',
            'subscribed_date' => 'Subscribed Date',
        ];
    }
}
