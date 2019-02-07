<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "branch_requests_verification".
 *
 * @property int $donate_id
 * @property int $branch_requests_id Branch Request ID
 * @property int $donor_id Donated By
 * @property int $paid_amount Donated Amount
 * @property int $verified Verified Status
 * @property string $donated_date
 *
 * @property BranchRequests $branchRequests
 * @property Users $donor
 */
class BranchRequestsVerification extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const DONATION_PENDING = 0;
    const DONATION_VERIFIED = 1;

    public $virtual_remain_amount;

    public static function tableName()
    {
        return 'branch_requests_verification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_requests_id', 'donor_id', 'paid_amount'], 'required'],
            [['donate_id', 'branch_requests_id', 'donor_id', 'verified', 'paid_amount', 'paid_amount'], 'integer'],
            [['donated_date'], 'safe'],
            [['virtual_remain_amount','paid_amount'], 'safe'],
            [['paid_amount'],'integer','min'=>1],
            /*['virtual_paid_amount', 'compare','compareAttribute'=>'virtual_remain_amount', 'operator' => '<=',
                //'message'=>'Buying amount should be bigger than bid amount',
                'type' => 'number'],*/
            //['paid_amount', 'compare', 'compareAttribute' => 'virtual_remain_amount', 'operator' => '<=', 'on' => self::SCENARIO_DEFAULT],
            [['donate_id'], 'unique'],
            [['branch_requests_id'], 'exist', 'skipOnError' => true, 'targetClass' => BranchRequests::className(), 'targetAttribute' => ['branch_requests_id' => 'req_id']],
            [['donor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['donor_id' => 'u_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'donate_id' => 'Donate ID',
            'branch_requests_id' => 'Branch Requests',
            'donor_id' => 'Donor',
            'verified' => 'Verified',
            'virtual_remain_amount' => 'Remain Amount',
            'paid_amount' => 'Paid Amount',
            'donated_date' => 'Donated Date',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['branch_requests_id','donor_id','paid_amount'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchRequests()
    {
        return $this->hasOne(BranchRequests::className(), ['req_id' => 'branch_requests_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonor()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'donor_id']);
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

    public function getBranchKeys(){
        $list = BranchRequests::find()
            ->where('paid_amount < blood_amount')
            ->andWhere('status != :status', [':status' => BranchRequests::REQUEST_CLOSED])
            ->orderBy('req_id')->all();
        return ArrayHelper::map($list,'req_id','req_key');
    }

    public function getDonorsList(){
        $list = Users::find()->orderBy('u_id')->all();
        return ArrayHelper::map($list,'u_id','username');
    }

    public static function getRemainAmount($req_id){
        $amounts = BranchRequests::find()
            ->select(["(blood_amount-paid_amount) as id", "(blood_amount-paid_amount) as name"])
            ->where(['req_id' => $req_id]) //6SkkZ1r5iG9Vas6 //EA5qb8J-ZIqezVL
            ->asArray()->all();
        return $amounts;
    }
}
