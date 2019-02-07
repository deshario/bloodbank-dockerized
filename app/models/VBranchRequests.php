<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_branch_requests".
 *
 * @property int $branch_id Requested By Branch
 * @property int $branchrequest_id
 * @property string $branch_name Branch Name
 * @property string $branch_address Branch Address
 * @property string $branch_code Branch Code
 * @property string $branch_lat_long Branch Latlong
 * @property string $blood_group Bloodgroup
 * @property string $blood_amount
 * @property string $paid_amount
 * @property string $created Requested Date
 * @property int $status Status
 */
class VBranchRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_branch_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'branch_name', 'branch_address', 'branch_code', 'branch_lat_long', 'blood_group', 'created'], 'required'],
            [['branch_id', 'branchrequest_id', 'status'], 'integer'],
            [['branch_address'], 'string'],
            [['blood_amount', 'paid_amount'], 'number'],
            [['created'], 'safe'],
            [['branch_name'], 'string', 'max' => 100],
            [['branch_code', 'blood_group'], 'string', 'max' => 45],
            [['branch_lat_long'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'branch_id' => 'Branch ID',
            'branchrequest_id' => 'Branchrequest ID',
            'branch_name' => 'Branch Name',
            'branch_address' => 'Branch Address',
            'branch_code' => 'Branch Code',
            'branch_lat_long' => 'Branch Lat Long',
            'blood_group' => 'Blood Group',
            'blood_amount' => 'Blood Amount',
            'paid_amount' => 'Paid Amount',
            'created' => 'Created',
            'status' => 'Status',
        ];
    }
}
