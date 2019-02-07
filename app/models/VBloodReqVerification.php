<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_donation_verified".
 *
 * @property string $donor Username
 * @property int $donor_id
 * @property string $receiver Username
 * @property int $receiver_id
 * @property string $blood_group Bloodgroup
 * @property int $blood_amount Required Blood Units
 * @property int $paid_amount Paid Blood Units
 * @property string $lat_long LatLong
 * @property string $full_address Full Address
 * @property string $reason Reason
 * @property string $postal_code Postal
 * @property string $request_key Secret Key
 * @property string $verified_by Username
 * @property int $donation_status Verified Status
 */
class VBloodReqVerification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_blood_req_verification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donor', 'receiver', 'blood_group', 'blood_amount', 'lat_long', 'request_key', 'verified_by'], 'required'],
            [['donor_id', 'receiver_id', 'blood_amount', 'paid_amount', 'donation_status'], 'integer'],
            [['full_address'], 'string'],
            [['donor', 'receiver', 'lat_long'], 'string', 'max' => 50],
            [['blood_group'], 'string', 'max' => 45],
            [['reason', 'request_key', 'verified_by'], 'string', 'max' => 255],
            [['postal_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'donor' => 'Donor',
            'donor_id' => 'Donor ID',
            'receiver' => 'Receiver',
            'receiver_id' => 'Receiver ID',
            'blood_group' => 'Blood Group',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'lat_long' => 'Lat Long',
            'full_address' => 'Full Address',
            'reason' => 'Reason',
            'postal_code' => 'Postal Code',
            'request_key' => 'Request Key',
            'verified_by' => 'Verified By',
            'donation_status' => 'Donation Status',
        ];
    }
}
