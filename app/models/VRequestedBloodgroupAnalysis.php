<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_requested_bloodgroup_analysis".
 *
 * @property string $blood_name Bloodgroup
 * @property string $requested_times
 */
class VRequestedBloodgroupAnalysis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_requested_bloodgroup_analysis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blood_name'], 'required'],
            [['requested_times'], 'integer'],
            [['blood_name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'blood_name' => 'Blood Name',
            'requested_times' => 'Requested Times',
        ];
    }
}
