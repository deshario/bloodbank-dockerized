<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "blood_requests".
 *
 * @property int $id ID
 * @property int $requester_id Requester ID
 * @property int $donater_id Donor ID
 * @property int $blood_group BloodGroup
 * @property int $blood_amount Required Blood Units
 * @property int $paid_amount Paid Blood Units
 * @property string $lat_long LatLong
 * @property string $location_name
 * @property string $full_address Full Address
 * @property string $reason Reason
 * @property string $postal_code Postal
 * @property string $created Created
 * @property string $req_key Secret Key
 * @property int $status Status
 *
 * @property BloodTypes $bloodGroup
 * @property Users $donater
 * @property Users $requester
 * @property BloodRequestsVerification[] $donationVerifieds
 * @property SavedRequests[] $savedRequests
 */
class BloodRequests extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const REQUESTS_INCOMING = 0;
    const REQUESTS_APPROVED = 1;
    const REQUESTS_CLOSED = 2;

    public $showActionColumn;
    public $showViewColumn;

    public $MRequired;
    public $MPaid;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blood_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requester_id', 'blood_group', 'blood_amount', 'lat_long','location_name'], 'required'],
            [['requester_id', 'status'], 'integer'],
            [['blood_group'], 'integer'],
            [['full_address'], 'string'],
            [['created','reason'], 'safe'],
            [['req_key'], 'unique'],
            [['blood_amount'], 'integer', 'min' => 0, 'max' => 20],
            [['lat_long', 'location_name'], 'string', 'max' => 50],
            [['req_key'], 'string', 'max' => 255],
            [['postal_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'requester_id' => 'Requester ID',
            'blood_group' => 'Blood Group',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'lat_long' => 'Location',
            'location_name' => 'Location Name',
            'full_address' => 'Full Address',
            'reason' => 'Reason',
            'postal_code' => 'Postal Code',
            'created' => 'Created',
            'req_key' => 'Request Key',
            'status' => 'Status',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['id','requester_id','blood_group','blood_amount','paid_amount','lat_long','location_name','full_address','reason','postal_code','created','req_key','status'];
        return $scenarios;
    }

    public $requestStatus = [
        self::REQUESTS_INCOMING => 'Pending',
        self::REQUESTS_APPROVED => 'Waiting For Donors',
        self::REQUESTS_CLOSED => 'Completed'
    ];

    public function getRequestStatus($status = null){
        if($status === null){
            return Yii::t('app',$this->requestStatus[$this->status]);
        }
        return Yii::t('app',$this->requestStatus[$status]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getBloodGroup()
    {
        return $this->hasOne(BloodTypes::className(), ['blood_id' => 'blood_group']);
    }

    public function getRequester()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'requester_id']);
    }

    public function getDonationVerifieds()
    {
        return $this->hasMany(BloodRequestsVerification::className(), ['request_id' => 'id']);
    }

    public function getSavedRequests()
    {
        return $this->hasMany(SavedRequests::className(), ['request_id' => 'id']);
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

    public function getRequesterName($id){
        $myusers = Users::findOne(['u_id' => $id])->count();
        if($myusers->username == null){
            return null;
        }else{
            return $myusers->username;
        }
    }

    public function getUsersList(){
        $list = Users::find()->orderBy('u_id')->all();
        return ArrayHelper::map($list,'u_id','username');
    }

    public function getBloodgroups(){
        $list = BloodTypes::find()->orderBy('blood_id')->all();
        return ArrayHelper::map($list,'blood_id','blood_name');
    }

    public function validateKey($key)
    {
        //$exists = ModelName::find()->where([ 'column_name' => $value])->andWhere(['column_name' => $value])->exists();
        $exists = BloodRequests::find()->where(['req_key' => $key])->exists();
        return $exists;
    }

    public function getRandomKey($length){
        return Yii::$app->getSecurity()->generateRandomString($length);
    }

    public function getCurrentDatetime(){
        //date_default_timezone_set("Asia/Bangkok");
        return date('Y-m-d H:i:s');
    }
}
