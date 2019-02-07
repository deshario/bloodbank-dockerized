<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * This is the model class for table "users".
 *
 * @property int $u_id
 * @property string $username Username
 * @property int $blood_group Blood Types ID
 * @property string $phone Phone no
 * @property string $profile_salt Salt
 * @property string $profile_password Encrypyted Password
 * @property string $created Registered Date
 * @property string $profile_token Firebase Token
 *
 * @property BloodRequests[] $bloodRequests
 * @property BloodRequests[] $bloodRequests0
 * @property BloodRequestsVerification[] $bloodRequestsVerifications
 * @property BloodRequestsVerification[] $bloodRequestsVerifications0
 * @property BranchRequestsVerification[] $branchRequestsVerifications
 * @property CampaignsSubscribed[] $campaignsSubscribeds
 * @property DonationDayReservation[] $donationDayReservations
 * @property SavedBloodRequests[] $savedBloodRequestsDonor
 * @property SavedBloodRequests[] $savedBloodRequestsReceiver
 * @property SavedBranchRequests[] $savedBranchRequests
 * @property BloodTypes $bloodGroup
 */
class Users extends \yii\db\ActiveRecord
{

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_UPDATE_TOKEN = 'update_token';
    const SCENARIO_REMOVE_TOKEN = 'remove_token';

    public $virtual_user;
    public $virtual_password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users'; // front_end_user
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'blood_group', 'profile_password', 'virtual_password'], 'required'],
            [['created','phone'], 'safe'],
            [['virtual_password','virtual_user'], 'safe'],
            [['profile_token'], 'string'],
            [['username'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 10],
            //[['profile_password'], 'string', 'max' => 100],
            [['username'], 'unique'],
            //[['profile_token'], 'unique'],
            [['profile_token'], 'unique', 'on' => 'register'],
            [['profile_token'], 'required', 'on' => 'login'],
            [['profile_token'], 'required', 'on' => 'register'],
            [['profile_token'], 'required', 'on' => 'update_token'],
            [['phone'], 'unique', 'on' => 'register'],
            [['blood_group'], 'exist', 'skipOnError' => true, 'targetClass' => BloodTypes::className(), 'targetAttribute' => ['blood_group' => 'blood_id']],
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
            'blood_group' => 'Blood Group',
            'phone' => 'Phone',
            'profile_salt' => 'Profile Salt',
            'profile_password' => 'Profile Password',
            'created' => 'Created',
            'profile_token' => 'Profile Token',
            'virtual_password' => 'Password',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['login'] = ['virtual_user','virtual_password','profile_token'];
        $scenarios['register'] = ['username','blood_group','phone','virtual_password','profile_token'];
        $scenarios['update_token'] = ['virtual_user','profile_token'];
        $scenarios['remove_token'] = ['virtual_user'];
        return $scenarios;
    }


//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getBloodDonater()
//    {
//        return $this->hasMany(BloodRequests::className(), ['donater_id' => 'u_id']);
//    }
//
//    public function getBloodRequester()
//    {
//        return $this->hasMany(BloodRequests::className(), ['requester_id' => 'u_id']);
//    }
//
//    public function getCampaignsSubscribeds()
//    {
//        return $this->hasMany(CampaignsSubscribed::className(), ['frontend_users_id' => 'u_id']);
//    }

    public function getDonationVerifieds()
    {
        return $this->hasMany(BloodRequestsVerification::className(), ['donated_by' => 'u_id']);
    }

    public function getDonationVerifieds0()
    {
        return $this->hasMany(BloodRequestsVerification::className(), ['donated_to' => 'u_id']);
    }

    public function getBloodRequestsVerifications()
    {
        return $this->hasMany(BloodRequestsVerification::className(), ['donated_by' => 'u_id']);
    }

    public function getBloodRequestsVerifications0()
    {
        return $this->hasMany(BloodRequestsVerification::className(), ['donated_to' => 'u_id']);
    }

    public function getBranchRequestsVerifications()
    {
        return $this->hasMany(BranchRequestsVerification::className(), ['donor_id' => 'u_id']);
    }

    public function getDonationDayReservations()
    {
        return $this->hasMany(DonationDayReservation::className(), ['user_id' => 'u_id']);
    }

    public function getBloodGroup()
    {
        return $this->hasOne(BloodTypes::className(), ['blood_id' => 'blood_group']);
    }

    public function getSavedBloodRequestsReceier()
    {
        return $this->hasMany(SavedBloodRequests::className(), ['donated_to' => 'u_id']);
    }

    public function getSavedBloodRequestsDonor()
    {
        return $this->hasMany(SavedBloodRequests::className(), ['donated_by' => 'u_id']);
    }

    public function getSavedBranchRequests()
    {
        return $this->hasMany(SavedBranchRequests::className(), ['saved_by' => 'u_id']);
    }

    public function getBloodgroups(){
        $list = BloodTypes::find()->orderBy('blood_id')->all();
        return ArrayHelper::map($list,'blood_id','blood_name');
    }

    public function gethashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    public function getAllActivatedTokens()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $url = Yii::$app->urlManager->createAbsoluteUrl(['/users/allactivetokens']);
        $content = file_get_contents($url);
        $data = json_decode($content);
        $Mtokens = array();
        foreach ($data as $tokens) {
            array_push($Mtokens,$tokens->profile_token);
        }
        return $Mtokens;
    }

    public function getActivatedTokensExceptMine($my_token)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $url = Yii::$app->urlManager->createAbsoluteUrl(['/users/OthersActiveTokens/'.$my_token]);
        $content = file_get_contents($url);
        $data = json_decode($content);
        $Mtokens = array();
        foreach ($data as $tokens) {
            array_push($Mtokens,$tokens->profile_token);
        }
        return $Mtokens;
    }

}
