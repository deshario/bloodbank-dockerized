<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequestsVerification */

$this->title = '#'.$model->branchRequests->branch->branch_code;
$this->params['breadcrumbs'][] = ['label' => 'Branch Requests Verifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-requests-verification-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><code><i><?= $this->title; ?></i></code></h3>
        </div>
        <div class="panel-body">

            <?php
            list($latitude,$longitude) = explode(',' , $model->branchRequests->branch->branch_lat_long);
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
                    //'branch_requests_id',
                    //'donor_id',
                    //'verified',
                    //'donated_date',
                    [
                        'label' => 'Donated By',
                        'value' => $model->donor->username
                    ],
                    [
                        'label' => 'Donated To',
                        'value' => $model->branchRequests->branch->branch_name,
                    ],
                    [
                        'label' => 'Blood Group',
                        'value' => $model->branchRequests->bloodGroup->blood_name,
                    ],
                    [
                        'label' => 'Blood Amount',
                        'value' => $model->branchRequests->blood_amount.' Units',
                    ],
                    [
                        'label' => 'Paid Amount',
                        'value' => $model->paid_amount,
                    ],
                    /*8[
                        'label' => 'Coordinates',
                        'value' => $model->branchRequests->branch->branch_lat_long,
                    ],*/
                    [
                        'label' => 'Full Address',
                        'value' => $model->branchRequests->branch->branch_address,
                    ],

                    [ 'format'=>'html','label' => 'Request Key', 'value' => '<code>#'.$model->branchRequests->req_key.'</code>'],
//                    ['format'=>'html', 'label' => 'Verified By', 'value' => '<code>'.$model->manager->username.'</code>'],
                    //[ 'format'=>'html', 'label' => 'Status', 'value' => '<code><i>'.$model->getDonationStatus($model->verified).'</i></code>'],
                ],
            ]) ?>
        </div>

    </div>

</div>
