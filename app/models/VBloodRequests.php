<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_blood_requests".
 *
 * @property int $id ID
 * @property int $requester_id Requester ID
 * @property string $requester_name Username
 * @property string $blood_group Bloodgroup
 * @property string $blood_amount
 * @property string $paid_amount
 * @property string $lat_long LatLong
 * @property string $location_name
 * @property string $full_address Full Address
 * @property string $reason Reason
 * @property string $postal_code Postal
 * @property string $created Created
 * @property string $req_key Secret Key
 * @property string $num_donors
 * @property int $status Status
 */
class VBloodRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_blood_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'requester_id', 'num_donors', 'status'], 'integer'],
            [['requester_id', 'requester_name', 'blood_group', 'lat_long', 'location_name', 'created', 'req_key'], 'required'],
            [['blood_amount', 'paid_amount'], 'number'],
            [['full_address'], 'string'],
            [['created'], 'safe'],
            [['requester_name', 'lat_long', 'location_name'], 'string', 'max' => 50],
            [['blood_group'], 'string', 'max' => 45],
            [['reason', 'req_key'], 'string', 'max' => 255],
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
            'requester_name' => 'Requester Name',
            'blood_group' => 'Blood Group',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'lat_long' => 'Lat Long',
            'location_name' => 'Location Name',
            'full_address' => 'Full Address',
            'reason' => 'Reason',
            'postal_code' => 'Postal Code',
            'created' => 'Created',
            'req_key' => 'Req Key',
            'num_donors' => 'Num Donors',
            'status' => 'Status',
        ];
    }
}
