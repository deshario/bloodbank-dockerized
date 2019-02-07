<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "saved_branch_requests".
 *
 * @property int $saved_id
 * @property int $requests_id BranchRequest ID
 * @property int $saved_by User ID
 * @property string $saved_date
 *
 * @property BranchRequests $requests
 * @property Users $savedBy
 */
class SavedBranchRequests extends \yii\db\ActiveRecord
{
    const REQUEST_CLOSED = 0;
    const REQUEST_OPEN = 1;

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'saved_branch_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requests_id', 'saved_by'], 'required'],
            [['requests_id', 'saved_by'], 'integer'],
            [['saved_date'], 'safe'],
            [['requests_id'], 'exist', 'skipOnError' => true, 'targetClass' => BranchRequests::className(), 'targetAttribute' => ['requests_id' => 'req_id']],
            [['saved_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['saved_by' => 'u_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'saved_id' => 'Saved ID',
            'requests_id' => 'Branch Requests',
            'saved_by' => 'Saved By',
            'saved_date' => 'Saved Date',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['requests_id','saved_by'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasOne(BranchRequests::className(), ['req_id' => 'requests_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSavedBy()
    {
        return $this->hasOne(Users::className(), ['u_id' => 'saved_by']);
    }

    public $requestStatus = [
        self::REQUEST_OPEN => 'OPENING',
        self::REQUEST_CLOSED => 'CLOSED'
    ];

    public function getRequestStatus($status = null){
        if($status === null){
            return Yii::t('app',$this->requestStatus[$this->status]);
        }
        return Yii::t('app',$this->requestStatus[$status]);
    }

    public function getValidateBranches()
    {
        $list = BranchRequests::find()
            ->where('status != :status', [':status' => BranchRequests::REQUEST_CLOSED])
            ->orderBy('req_id')->all();
        return ArrayHelper::map($list, 'req_id', 'req_key');
    }

    public function getUsersList()
    {
        $list = Users::find()->orderBy('u_id')->all();
        return ArrayHelper::map($list, 'u_id', 'username');
    }

    public function checkSaved($model)
    {
        $check = SavedBranchRequests::find()
            ->where('requests_id = ' . $model->requests_id)
            ->andWhere('saved_by = :saved_by', [':saved_by' => $model->saved_by])
            ->all();
        if (count($check) > 0) { // Already Exists
            return false;
        } else {
            return true; // New Record
        }
    }

}
