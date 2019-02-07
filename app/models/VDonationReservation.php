<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_donation_reservation".
 *
 * @property int $reserved_id
 * @property int $user_id Reserved By
 * @property string $username Username
 * @property int $branch_id Reserved Branch
 * @property string $branch_name Branch Name
 * @property string $user_notes User Notes
 * @property string $reservation_key Reservation Key
 * @property string $reserved_date Reserved Date
 */
class VDonationReservation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_donation_reservation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reserved_id', 'user_id', 'branch_id'], 'integer'],
            [['user_id', 'username', 'branch_id', 'branch_name', 'reservation_key', 'reserved_date'], 'required'],
            [['reserved_date'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['branch_name', 'user_notes'], 'string', 'max' => 100],
            [['reservation_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reserved_id' => 'Reserved ID',
            'user_id' => 'User ID',
            'username' => 'Username',
            'branch_id' => 'Branch ID',
            'branch_name' => 'Branch Name',
            'user_notes' => 'User Notes',
            'reservation_key' => 'Reservation Key',
            'reserved_date' => 'Reserved Date',
        ];
    }
}
