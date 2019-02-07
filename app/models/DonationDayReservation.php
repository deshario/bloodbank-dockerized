<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "donation_day_reservation".
 *
 * @property int $reserved_id
 * @property int $user_id Reserved By
 * @property int $branch_id Reserved Branch
 * @property string $user_notes User Notes
 * @property string $reservation_key Reservation Key
 * @property string $reserved_date Reserved Date
 *
 * @property Users $user
 * @property Branch $branch
 */
class DonationDayReservation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return 'donation_day_reservation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'branch_id', 'reserved_date'], 'required'],
            [['user_id', 'branch_id'], 'integer'],
            [['reserved_date'], 'safe'],
            [['user_notes'], 'string', 'max' => 100],
            [['reservation_key'], 'string', 'max' => 255],
            [['reservation_key'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'u_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'branch_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reserved_id' => 'Reserved ID',
            'user_id' => 'Reserved By',
            'branch_id' => 'Reserved Branch',
            'user_notes' => 'User Notes',
            'reservation_key' => 'Reservation Key',
            'reserved_date' => 'Reserved Date',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['user_id','branch_id','reserved_date','user_notes'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['branch_id' => 'branch_id']);
    }

    public function getUsersList(){
        $list = Users::find()->orderBy('u_id')->all();
        return ArrayHelper::map($list,'u_id','username');
    }

    public function getBranchList(){
        $list = Branch::find()->orderBy('branch_id')->all();
        return ArrayHelper::map($list,'branch_id','branch_name');
    }

    public function validateKey($key){
        $exists = DonationDayReservation::find()->where(['reservation_key' => $key])->exists();
        return $exists;
    }

    public function getRandomKey($length){
        $currentDateTime = date('dm-His-');
        $keys = array_merge(range(0,9), range('a', 'z'));
        $key = "";
        for($i=0; $i < $length; $i++) {
            $key .= $keys[mt_rand(0, count($keys) - 1)];
        }
        return "RD-".$currentDateTime.$key;
    }
}
