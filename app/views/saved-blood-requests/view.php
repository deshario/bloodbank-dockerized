<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBloodRequests */

$this->title = $model->savedBy->username;
$this->params['breadcrumbs'][] = ['label' => 'Saved Blood Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saved-blood-requests-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><code><i><?= $this->title; ?></i></code></h3>
        </div>
        <div class="panel-body">
            <?php
            list($latitude, $longitude) = explode(',', $model->request->lat_long);
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
                    'content' => $model->request->location_name
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
                    //'saved_id',
                    //'request_id',
                    //'saved_by',

                    ['label' => 'Request Token', 'format' => 'html', 'value' => '<code>'.$model->request->req_key.'</code>'],
                    ['label' => 'Saved By', 'value' => $model->savedBy->username],
                    ['label' => 'Bloodgroup', 'value' => $model->request->bloodGroup->blood_name],
                    ['label' => 'Amounts', 'value' => $model->request->blood_amount . ' Units'],
                    ['label' => 'Location', 'value' => $model->request->location_name],
                    'saved_date',
                    ['label' => 'Status', 'format' => 'html', 'value' => '<code><i>'.$model->getRequestStatus($model->request->status) . '</i></code>'],
                ],
            ]) ?>
        </div>
    </div>

</div>
