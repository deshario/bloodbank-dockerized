<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "saved_blood_requests".
 *
 * @property int $saved_id ID
 * @property int $request_id BloodRequest ID
 * @property int $saved_by Donor ID
 * @property string $saved_date Saved Date
 *
 * @property Users $savedBy
 * @property BloodRequests $request
 */
class SavedBloodRequests extends \yii\db\ActiveRecord
{
    const REQUESTS_INCOMING = 0;
    const REQUESTS_APPROVED = 1;
    const REQUESTS_CLOSED = 2;

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'saved_blood_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'saved_by'], 'required'],
            [['request_id', 'saved_by'], 'integer'],
            [['saved_date'], 'safe'],
            [['saved_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['saved_by' => 'u_id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => BloodRequests::className(), 'targetAttribute' => ['request_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'saved_id' => 'Saved ID',
            'request_id' => 'Request ID',
            'saved_by' => 'Saved By',
            'saved_date' => 'Saved Date',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['request_id','saved_by'];
        return $scenarios;
    }

    public function getSavedBy()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'saved_by']);
    }

    public function getRequest()
    {
        return $this->hasOne(BloodRequests::className(), ['id' => 'request_id']);
    }

    public function getRequestsList()
    {
        $list = BloodRequests::find()
            ->where('status != :status', [':status' => BloodRequests::REQUESTS_CLOSED])
            ->orderBy('id')->all();
        return ArrayHelper::map($list, 'id', 'req_key');
    }

    public function getUsersList()
    {
        $list = Users::find()->orderBy('u_id')->all();
        return ArrayHelper::map($list, 'u_id', 'username');
    }

    public function checkSaved($model)
    {
        $check = SavedBloodRequests::find()
            ->where('request_id = ' . $model->request_id)
            ->andWhere('saved_by = :saved_by', [':saved_by' => $model->saved_by])
            ->all();
        if (count($check) > 0) { // Already Exists
            return false;
        } else {
            return true; // New Record
        }
    }

    public $requestStatus = [
        self::REQUESTS_INCOMING => 'Pending',
        self::REQUESTS_APPROVED => 'Waiting For Donors',
        self::REQUESTS_CLOSED => 'Completed'
    ];

    public function getRequestStatus($status = null){
        if($status === null){
            return Yii::t('app',$this->requestStatus[$this->status]);
        }
        return Yii::t('app',$this->requestStatus[$status]);
    }
}
