<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "blood_types".
 *
 * @property int $blood_id ID
 * @property string $blood_name Bloodgroup
 *
 * @property BloodRequests[] $bloodRequests
 * @property BranchRequests[] $branchRequests
 */
class BloodTypes extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blood_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blood_name'], 'required'],
            [['blood_name'], 'string', 'max' => 45],
            [['blood_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'blood_id' => 'Blood ID',
            'blood_name' => 'Blood Name',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['blood_id','blood_name'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodRequests()
    {
        return $this->hasMany(BloodRequests::className(), ['blood_group' => 'blood_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchRequests()
    {
        return $this->hasMany(BranchRequests::className(), ['blood_group' => 'blood_id']);
    }
}
