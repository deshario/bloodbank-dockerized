<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_request_status_analysis".
 *
 * @property string $status_alias
 * @property string $status_count
 */
class VRequestStatusAnalysis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_request_status_analysis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_count'], 'integer'],
            [['status_alias'], 'string', 'max' => 9],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_alias' => 'Status Alias',
            'status_count' => 'Status Count',
        ];
    }
}
