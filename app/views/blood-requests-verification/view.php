<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequestsVerification */

$this->title = '#'.$model->request->req_key;
$this->params['breadcrumbs'][] = ['label' => 'Blood Requests Verifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="donation-verified-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><code><i><?= $this->title; ?></i></code></h3>
        </div>
        <div class="panel-body">

            <?php
            list($latitude,$longitude) = explode(',' , $model->request->lat_long);
            echo \pigolab\locationpicker\LocationPickerWidget::widget([
                'key' => 'AIzaSyAfbsZQhZDh-bZIAZbS1ZSHDsYzRBsX1FY',	// require , Put your google map api key
                'options' => [
                    'style' => 'width: 100%; height: 400px', // map canvas width and height
                ] ,
                'clientOptions' => [
                    'location' => [
                        'latitude'  => $latitude,
                        'longitude' => $longitude,
                    ],
                ]
            ]);
            ?>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'donate_id',
                    //'request_id',
                    //'donated_by',
                    //'donated_to',
                    //'manager_id',
                    //'verified',
                    //'donated_date',
                    [
                        'label' => 'Donor',
                        'value' => $model->donatedBy->username
                    ],
                    [
                        'label' => 'Receiver',
                        'value' => $model->donatedTo->username
                    ],
                    [
                        'label' => 'Blood Group',
                        'value' => $model->request->bloodGroup->blood_name,
                    ],
                    [
                        'label' => 'Blood Amount',
                        'value' => $model->request->blood_amount.' Units',
                    ],
                    [
                        'label' => 'Paid Amount',
                        'value' => $model->paid_amount,
                    ],
                    [
                        'label' => 'Coordinates',
                        'value' => $model->request->lat_long,
                    ],
                    [
                        'label' => 'Full Address',
                        'value' => $model->request->full_address,
                    ],
                    [
                        'label' => 'Reason',
                        'value' => $model->request->reason,
                    ],
                    [
                        'label' => 'Postal Code',
                        'value' => $model->request->postal_code,
                    ],
                    ['format'=>'html', 'label' => 'Verified By', 'value' => '<code>'.$model->getManagerName($model->manager_id).'</code>'],
                    [ 'format'=>'html','label' => 'Request Key', 'value' => '<code>#'.$model->request->req_key.'</code>'],
                    //[ 'format'=>'html', 'label' => 'Status', 'value' => '<code><i>'.$model->getDonationStatus($model->verified).'</i></code>'],
                ],
            ]) ?>
        </div>

    </div>

</div>