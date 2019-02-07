<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_saved_bloodreq".
 *
 * @property int $donor_id
 * @property string $donor_name Username
 * @property int $requester_id Requester ID
 * @property int $bloodreq_id ID
 * @property string $blood_name Bloodgroup
 * @property int $blood_amount Required Blood Units
 * @property int $paid_amount Paid Blood Units
 * @property string $location_name
 * @property string $lat_long LatLong
 * @property string $full_address Full Address
 * @property string $req_key Request Key
 * @property int $req_status Status
 */
class VSavedBloodreq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_saved_bloodreq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donor_id', 'requester_id', 'bloodreq_id', 'blood_amount', 'paid_amount', 'req_status'], 'integer'],
            [['donor_name', 'requester_id', 'blood_name', 'blood_amount', 'location_name', 'lat_long', 'req_key'], 'required'],
            [['full_address'], 'string'],
            [['donor_name', 'location_name', 'lat_long'], 'string', 'max' => 50],
            [['blood_name'], 'string', 'max' => 45],
            [['req_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'donor_id' => 'Donor ID',
            'donor_name' => 'Donor Name',
            'requester_id' => 'Requester ID',
            'bloodreq_id' => 'Bloodreq ID',
            'blood_name' => 'Blood Name',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'location_name' => 'Location Name',
            'lat_long' => 'Lat Long',
            'full_address' => 'Full Address',
            'req_key' => 'Req Key',
            'req_status' => 'Req Status',
        ];
    }
}
