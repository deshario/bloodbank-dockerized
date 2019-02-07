<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBranchRequests */

$this->title = $model->savedBy->username;
$this->params['breadcrumbs'][] = ['label' => 'Saved Branch Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saved-branch-requests-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><code><i><?= $this->title; ?></i></code></h3>
        </div>
        <div class="panel-body">
            <?php
            list($latitude, $longitude) = explode(',', $model->requests->branch->branch_lat_long);
            $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);
            $map = new Map([
                'center' => $coord,
                'zoom' => 16,
                'width' => '100%',
                'height' => '200',
                //'mapTypeId' => \dosamigos\google\maps\MapTypeId::SATELLITE,
            ]);
            $marker = new Marker([
                'position' => $coord,
            ]);
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => $model->requests->branch->branch_name
                ])
            );
            // Add marker to the map
            $map->addOverlay($marker);
            echo $map->display();
            ?>

            <?= DetailView::widget([
                'model' => $model,
                'template'=>'<tr><th width="20%">{label}</th><td> {value}</td></tr>',
                'attributes' => [

//                    'saved_id',
//                    'requests_id',
//                    'saved_by',
//                    'saved_date',

                    ['label' => 'Request Token', 'format' => 'html', 'value' => '<code>'.$model->requests->req_key.'</code>'],
                    ['label' => 'Branch Name', 'value' => $model->requests->branch->branch_name],
                    ['label' => 'Brannch Code', 'format' => 'html', 'value' => '<code><i>'.$model->requests->branch->branch_code.'</i></code>'],
                    ['label' => 'Saved By', 'value' => $model->savedBy->username],
                    ['label' => 'Bloodgroup', 'value' => $model->requests->bloodGroup->blood_name],
                    ['label' => 'Amounts', 'value' => $model->requests->blood_amount . ' Units'],
                    'saved_date',
                    ['label' => 'Status', 'format' => 'html', 'value' => '<code><i>'.$model->getRequestStatus($model->requests->status).'</i></code>'],
                ],
            ]) ?>
        </div>
    </div>

</div>
