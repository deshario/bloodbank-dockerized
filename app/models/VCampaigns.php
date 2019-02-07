<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_campaigns".
 *
 * @property int $campaign_id
 * @property string $campaign_name Campaign Name
 * @property string $campaign_desc Campaign Description
 * @property string $campaign_img Campaign Img
 * @property string $campaign_created Created At
 * @property string $campaign_coordinates Campaign Latlong
 * @property string $campaign_address Campaign Address
 * @property string $campaign_key Campaign Key
 * @property string $campaign_creator Username
 * @property string $campaign_joined
 * @property int $campaign_status Available Status
 */
class VCampaigns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_campaigns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'campaign_joined', 'campaign_status'], 'integer'],
            [['campaign_name', 'campaign_desc', 'campaign_img', 'campaign_created', 'campaign_coordinates', 'campaign_address', 'campaign_key', 'campaign_creator'], 'required'],
            [['campaign_created'], 'safe'],
            [['campaign_address'], 'string'],
            [['campaign_name', 'campaign_img'], 'string', 'max' => 100],
            [['campaign_desc', 'campaign_key', 'campaign_creator'], 'string', 'max' => 255],
            [['campaign_coordinates'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'campaign_id' => 'Campaign ID',
            'campaign_name' => 'Campaign Name',
            'campaign_desc' => 'Campaign Desc',
            'campaign_img' => 'Campaign Img',
            'campaign_created' => 'Campaign Created',
            'campaign_coordinates' => 'Campaign Coordinates',
            'campaign_address' => 'Campaign Address',
            'campaign_key' => 'Campaign Key',
            'campaign_creator' => 'Campaign Creator',
            'campaign_joined' => 'Campaign Joined',
            'campaign_status' => 'Campaign Status',
        ];
    }
}
