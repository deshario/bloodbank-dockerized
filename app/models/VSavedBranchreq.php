<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_saved_branchreq".
 *
 * @property int $user_id
 * @property string $user_name Username
 * @property string $blood_name Bloodgroup
 * @property int $blood_amount Required Blood Units
 * @property int $paid_amount Paid Blood Units
 * @property int $branchreq_id
 * @property string $branch_name Branch Name
 * @property string $branch_code Branch Code
 * @property string $branch_lat_long Branch Latlong
 * @property string $branch_address Branch Address
 * @property string $req_key Request Key
 * @property int $req_status Status
 */
class VSavedBranchreq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_saved_branchreq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'blood_amount', 'paid_amount', 'branchreq_id', 'req_status'], 'integer'],
            [['user_name', 'blood_name', 'blood_amount', 'branch_name', 'branch_code', 'branch_lat_long', 'branch_address', 'req_key'], 'required'],
            [['branch_address'], 'string'],
            [['user_name', 'branch_lat_long'], 'string', 'max' => 50],
            [['blood_name', 'branch_code'], 'string', 'max' => 45],
            [['branch_name'], 'string', 'max' => 100],
            [['req_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'blood_name' => 'Blood Name',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'branchreq_id' => 'Branchreq ID',
            'branch_name' => 'Branch Name',
            'branch_code' => 'Branch Code',
            'branch_lat_long' => 'Branch Lat Long',
            'branch_address' => 'Branch Address',
            'req_key' => 'Req Key',
            'req_status' => 'Req Status',
        ];
    }
}
