<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

/**
 * This is the model class for table "campaigns".
 *
 * @property int $campaign_id
 * @property string $campaign_name Campaign Name
 * @property string $campaign_desc Campaign Description
 * @property string $campaign_img Campaign Img
 * @property string $campaign_created Created At
 * @property string $campaign_coordinates Campaign Latlong
 * @property string $campaign_address Campaign Address
 * @property string $campaign_key Campaign Key
 * @property int $campaign_creator Campaign Created By
 * @property int $campaign_status Available Status
 *
 * @property Managers $campaignCreator
 * @property CampaignsSubscribed[] $campaignsSubscribeds
 */
class Campaigns extends \yii\db\ActiveRecord
{

    const CAMP_ACTIVE = 1;
    const CAMP_INACTIVE = 0;

    const DATE_SHORT = 1;
    const DATE_MEDIUM = 2;

    public $virtual_creator;

    public $broadcast_title;
    public $broadcast_message;

    public $campaignStatus = [
        self::CAMP_ACTIVE => 'ACTIVE',
        self::CAMP_INACTIVE => 'INACTIVE'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaigns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_name', 'campaign_desc', 'campaign_img', 'campaign_coordinates'], 'required'],
            [['campaign_created'], 'safe'],
            [['virtual_creator'], 'safe'],
            [['campaign_address','broadcast_title','broadcast_message'], 'string'],

            //[['campaign_name'], 'string', 'min' => 15, 'max' => 100],
            //[['campaign_desc'], 'string', 'min' => 30],

            //[['broadcast_title'], 'required', 'on' => self::SCENARIO_BROADCAST, 'skipOnEmpty' => false],
            //[['broadcast_message'], 'required', 'on' => self::SCENARIO_BROADCAST, 'skipOnEmpty' => false],

            ['broadcast_title', 'required', 'when' => function($model) {
                return $model->broadcast_title != '';
            }],

            ['broadcast_message', 'required', 'when' => function($model) {
                return $model->broadcast_message != '';
            }],

            [['campaign_creator', 'campaign_status'], 'integer'],
            [['campaign_name'], 'string', 'max' => 100],
            [['campaign_desc', 'campaign_key'], 'string', 'max' => 255],
            [['campaign_coordinates'], 'string', 'max' => 50],

            [['campaign_img'], 'file', 'extensions'=>'jpg, gif, png'],
            [['campaign_img'], 'file', 'maxSize'=>'200000'],

            [['campaign_creator'], 'exist', 'skipOnError' => true, 'targetClass' => Managers::className(), 'targetAttribute' => ['campaign_creator' => 'id']],
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
            'campaign_coordinates' => 'Campaign Location',
            'campaign_address' => 'Campaign Address',
            'campaign_key' => 'Campaign Key',
            'campaign_creator' => 'Campaign Creator',
            'virtual_creator' => 'Virtual Creator',
            'campaign_status' => 'Campaign Status',
            'broadcast_title' => 'Title',
            'broadcast_message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignCreator()
    {
        return $this->hasOne(Managers::className(), ['id' => 'campaign_creator']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignsSubscribeds()
    {
        return $this->hasMany(CampaignsSubscribed::className(), ['campaigns_id' => 'campaign_id']);
    }

    public function getCampaignStatus($status = null){
        if($status === null){
            return Yii::t('app',$this->campaignStatus[$this->status]);
        }
        return Yii::t('app',$this->campaignStatus[$status]);
    }

    public function getCustomDate($date, $length = null, $time = false){
        $myArray = explode(' ', $date);
        $Mdate = explode('-', $myArray[0]);
        $Mtime = explode(':', $myArray[1]);

        $day = $Mdate[2];
        $month = $Mdate[1];
        $year = $Mdate[0];
        $hour = $Mtime[0];
        $min = $Mtime[1];
        $sec = $Mtime[2];

        $months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $output = "";

        if($length == null){
            $output = $day." ".$months[$month-1]." ".$year.", ".$hour.":".$min.":".$sec;
        }else{
            if($length == BranchRequests::DATE_SHORT){
                $output =  $day."/".$month."/".$year;
            }else{
                $output = $months[$month-1]." ".$day.", ".$year;
            }
        }

        if($time == true){
            $output = $output." ".$hour.":".$min;
        }
        return $output;
    }

    public function getRandomKey($length){
        return Yii::$app->getSecurity()->generateRandomString($length);
    }

    public function getSubscribedTokens($campaign_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $url = Yii::$app->urlManager->createAbsoluteUrl(['/campaigns/subscribers/'.$campaign_id]);
        $content = file_get_contents($url);
        $data = json_decode($content);
        $Mtokens = array();
        foreach ($data as $tokens) {
            array_push($Mtokens,$tokens->profile_token);
        }
        return $Mtokens;
    }
}
