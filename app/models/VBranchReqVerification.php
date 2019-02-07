<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_branch_req_verification".
 *
 * @property int $donor_id Donated By
 * @property string $donate_by Username
 * @property int $branch_id
 * @property string $branch_name Branch Name
 * @property string $blood_group Bloodgroup
 * @property int $blood_amount Required Blood Units
 * @property int $paid_amount Paid Blood Units
 * @property string $latlong Branch Latlong
 * @property string $full_address Branch Address
 * @property string $req_key Request Key
 * @property string $donated_date
 * @property int $verified Verified Status
 */
class VBranchReqVerification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_branch_req_verification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donor_id', 'donate_by', 'branch_name', 'blood_group', 'blood_amount', 'latlong', 'full_address', 'req_key'], 'required'],
            [['donor_id', 'branch_id', 'blood_amount', 'paid_amount', 'verified'], 'integer'],
            [['full_address'], 'string'],
            [['donated_date'], 'safe'],
            [['donate_by', 'latlong'], 'string', 'max' => 50],
            [['branch_name'], 'string', 'max' => 100],
            [['blood_group'], 'string', 'max' => 45],
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
            'donate_by' => 'Donate By',
            'branch_id' => 'Branch ID',
            'branch_name' => 'Branch Name',
            'blood_group' => 'Blood Group',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'latlong' => 'Latlong',
            'full_address' => 'Full Address',
            'req_key' => 'Req Key',
            'donated_date' => 'Donated Date',
            'verified' => 'Verified',
        ];
    }
}
