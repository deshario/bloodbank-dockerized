<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property int $branch_id
 * @property string $branch_name Branch Name
 * @property string $branch_lat_long Branch Latlong
 * @property string $branch_address Branch Address
 * @property string $branch_code Branch Code
 * @property string $branch_created Branch Created
 *
 * @property BranchRequests[] $branchRequests
 * @property Managers[] $managers
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_name', 'branch_lat_long'], 'required'],
            [['branch_created'], 'safe'],
            [['branch_name'], 'string', 'min' => 5, 'max' => 100],
            ['branch_name', 'match', 'pattern' => '/^[A-Za-z\s]+$/', 'message' => 'Invalid characters (Please use a-z only)'],
            [['branch_lat_long'], 'string', 'max' => 50],
            [['branch_address'], 'string', 'max' => 255],
            [['branch_code'], 'string', 'max' => 45],
            [['branch_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'branch_id' => 'Branch ID',
            'branch_name' => 'Branch Name',
            'branch_lat_long' => 'Branch Location',
            'branch_address' => 'Branch Address',
            'branch_code' => 'Branch Code',
            'branch_created' => 'Branch Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchRequests()
    {
        return $this->hasMany(BranchRequests::className(), ['branch_id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManagers()
    {
        return $this->hasMany(Managers::className(), ['worked_at' => 'branch_id']);
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
            //$output = $day." ".$months[$month-1]." ".$year.", ".$hour.":".$min.":".$sec;
            $output = $months[$month-1]." ".$day.", ".$year;
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

    public static function getDatetime($timestamp){
        $Mtime = strtotime(date("Y-m-d H:i:s", $timestamp) . " +7 hours"); // Add +7 hours to timestamp
        $newTimeStamp = new DateTime("@$Mtime");  // convert UNIX timestamp to PHP DateTime
        $dateTime = $newTimeStamp->format('Y-m-d H:i:s');
        return $dateTime;
    }
}
