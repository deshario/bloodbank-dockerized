<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "branch_requests".
 *
 * @property int $req_id
 * @property int $branch_id Requested By Branch
 * @property int $blood_group BloodGroup
 * @property int $blood_amount Required Blood Units
 * @property int $paid_amount Paid Blood Units
 * @property string $created Requested Date
 * @property string $req_key Request Key
 * @property int $status Status
 *
 * @property BloodTypes $bloodGroup
 * @property Branch $branch
 * @property BranchRequestsVerification[] $branchRequestsVerifications
 */
class BranchRequests extends \yii\db\ActiveRecord
{
    const REQUEST_CLOSED = 0;
    const REQUEST_OPEN = 1;

    const DATE_SHORT = 1;
    const DATE_MEDIUM = 2;

    public $virtual_remain_amount;
    public $virtual_paid_amount;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'blood_group'], 'required'],
            [['branch_id', 'blood_group', 'blood_amount', 'paid_amount', 'status', 'virtual_remain_amount', 'virtual_paid_amount'], 'integer'],
            [['created'], 'safe'],
            [['virtual_remain_amount','virtual_paid_amount'], 'safe'],
            [['virtual_paid_amount'],'integer','min'=>1],

            ['virtual_paid_amount', 'compare','compareAttribute'=>'virtual_remain_amount', 'operator' => '<=',
                //'message'=>'Buying amount should be bigger than bid amount',
                'type' => 'number'],

            [['blood_group'], 'exist', 'skipOnError' => true, 'targetClass' => BloodTypes::className(), 'targetAttribute' => ['blood_group' => 'blood_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'branch_id']],
        ];
    }

// branch_id blood_group blood_amount paid_amount status created

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'req_id' => 'Req ID',
            'branch_id' => 'Branch Name',
            'blood_group' => 'Blood Group',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'virtual_remain_amount' => 'Remain Amount',
            'virtual_paid_amount' => 'Paid Amount',
            'created' => 'Created',
            'status' => 'Status',
            'req_key' => 'Req Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public $requestStatus = [
        self::REQUEST_OPEN => 'OPENING',
        self::REQUEST_CLOSED => 'CLOSED'
    ];

    public function getRequestStatus($status = null){
        if($status === null){
            return Yii::t('app',$this->requestStatus[$this->status]);
        }
        return Yii::t('app',$this->requestStatus[$status]);
    }

    public function getBloodGroup()
    {
        return $this->hasOne(BloodTypes::className(), ['blood_id' => 'blood_group']);
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['branch_id' => 'branch_id']);
    }

    public function getBranchRequestsVerifications()
    {
        return $this->hasMany(BranchRequestsVerification::className(), ['branch_requests_id' => 'req_id']);
    }


    public function validateKey($key)
    {
        //$exists = ModelName::find()->where([ 'column_name' => $value])->andWhere(['column_name' => $value])->exists();
        $exists = BranchRequests::find()->where(['req_key' => $key])->exists();
        return $exists;
    }

    public function getRandomKey($length){
        return Yii::$app->getSecurity()->generateRandomString($length);
    }

    public function getCorrectAmount($amount = null){
        if($amount === null){
            return null;
        }
        return $amount.' Units';
    }

    public static function getCustomDate($date, $length = null, $time = false){
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

    public static function getBloodgroups(){
        $list = BloodTypes::find()->orderBy('blood_id')->all();
        return ArrayHelper::map($list,'blood_id','blood_name');
    }

    public static function getBranchList(){
        $list = Branch::find()->orderBy('branch_id')->all();
        return ArrayHelper::map($list,'branch_id','branch_name');
    }

    public static function getCustomStatus(){
        $array = [
            ['id' => BranchRequests::REQUEST_OPEN, 'name' => 'OPENING',],
            ['id' => BranchRequests::REQUEST_CLOSED, 'name' => 'CLOSE',],
        ];
        return ArrayHelper::map($array, 'id', 'name');
    }

}
