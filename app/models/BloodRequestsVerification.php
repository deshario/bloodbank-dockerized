<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "blood_requests_verification".
 *
 * @property int $donate_id
 * @property int $request_id Blood Requests ID
 * @property int $donated_by Donater
 * @property int $donated_to Requester
 * @property int $manager_id Verified By
 * @property int $paid_amount Donated Amount
 * @property int $verified Verified Status
 * @property string $donated_date Donated Date
 *
 * @property Users $donatedBy
 * @property Users $donatedTo
 * @property BloodRequests $request
 * @property Managers $manager
 */
class BloodRequestsVerification extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const DONATION_PENDING = 0;
    const DONATION_VERIFIED = 1;

    public $virtual_remain_amount;

    public static function tableName()
    {
        return 'blood_requests_verification';
    }

    public function rules()
    {
        return [
            [['request_id', 'donated_by', 'donated_to', 'paid_amount'], 'required'],
            [['request_id', 'donated_by', 'donated_to', 'paid_amount', 'manager_id', 'verified', 'virtual_remain_amount'], 'integer'],
            [['donated_date'], 'safe'],
            [['virtual_remain_amount','paid_amount'], 'safe'],
            [['paid_amount'],'integer','min'=>1],

            //['virtual_paid_amount', 'compare','compareAttribute'=>'virtual_remain_amount', 'operator' => '<=', 'type' => 'number'],
            //['paid_amount', 'compare', 'compareAttribute' => 'virtual_remain_amount', 'operator' => '<=', 'on' => self::SCENARIO_DEFAULT],
            //['paid_amount', 'compare', 'compareAttribute' => 'virtual_remain_amount', 'operator' => '<=', 'on' => 'create'],

            ['donated_to', 'compare','compareAttribute'=>'donated_by', 'operator' => '!=', 'type' => 'number'],

            [['donated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['donated_by' => 'u_id']],
            [['donated_to'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['donated_to' => 'u_id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => BloodRequests::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Managers::className(), 'targetAttribute' => ['manager_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'donate_id' => 'Donate ID',
            'request_id' => 'Blood Request',
            'donated_by' => 'Donor',
            'donated_to' => 'Receiver',
            'manager_id' => 'Manager',
            'virtual_remain_amount' => 'Remain Amount',
            'paid_amount' => 'Paid Amount',
            'verified' => 'Donation Status',
            'donated_date' => 'Donated Date',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['request_id','donated_by','donated_to','paid_amount'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonatedBy()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'donated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonatedTo()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'donated_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(BloodRequests::className(), ['id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Managers::className(), ['id' => 'manager_id']);
    }

    public function getManagerName($id)
    {
        if($id == null){
            return 'None';
        }else{
            return $this->manager->username;
        }
    }

    public function getRequestsList(){
        $list = BloodRequests::find()->where('paid_amount < blood_amount')
            ->andWhere('status != :status', [':status' => BloodRequests::REQUESTS_CLOSED])
            ->orderBy('id')->all();
        return ArrayHelper::map($list,'id','req_key');
    }

    public function getUsersList(){
        $list = Users::find()->orderBy('u_id')->all();
        return ArrayHelper::map($list,'u_id','username');
    }

    public function getManagersList(){
        $list = Managers::find()->orderBy('id')->all();
        return ArrayHelper::map($list,'id','username');
    }

    public $donationStatus = [
        self::DONATION_PENDING => 'Pending',
        self::DONATION_VERIFIED => 'Verified'
    ];

    public function getDonationStatus($status = null){
        if($status === null){
            return Yii::t('app',$this->donationStatus[$this->status]);
        }
        return Yii::t('app',$this->donationStatus[$status]);
    }

    public function getCustomDate($date){
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
        //return $months[$month-1]." ".$day.", ".$year." ".$hour.":".$min;
        return $day." ".$months[$month-1]." ".$year.", ".$hour.":".$min.":".$sec;
    }

    public static function getRemainAmount($req_id){
        $amounts = BloodRequests::find()
            ->select(["(blood_amount-paid_amount) as id", "(blood_amount-paid_amount) as name"]) // if paid amount is 0 will be problem at validation
            ->where(['id' => $req_id]) //6SkkZ1r5iG9Vas6 //EA5qb8J-ZIqezVL
            ->asArray()->all();
        return $amounts;
    }

}
