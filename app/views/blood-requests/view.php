<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequests */

$this->title = '#'.$model->req_key;
$this->params['breadcrumbs'][] = ['label' => 'Blood Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//$this->registerCss(".point_me{cursor:pointer} .point_me:hover{ text-decoration: underline}");
$this->registerCss(".point_me{cursor:pointer}");
?>

<div class="blood-requests-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><code><i><?= $this->title; ?></i></code></h3>
        </div>
        <div class="panel-body">

            <?php
            list($latitude, $longitude) = explode(',', $model->lat_long);
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
                'title' => $model->requester_id,
            ]);
            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => $model->requester_id
                ])
            );
            // Add marker to the map
            $map->addOverlay($marker);
            echo $map->display();
            ?>


          <!--
            <?php
          list($latitude,$longitude) = explode(',' , $model->lat_long);
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
          -->

            <?= DetailView::widget([
                'model' => $model,
                'template'=>'<tr><th width="20%">{label}</th><td> {value}</td></tr>',
                'attributes' => [
                    //'id',
                    //'requester_id',
                    //'donater_id',
                    //'requester.username',
                    //'donater.username',
                    [
                        'label' => 'Requester',
                        'value' => $model->requester->username
                    ],
                    [
                        'label' => 'Blood Group',
                        'value' => $model->bloodGroup->blood_name,
                    ],
                    [
                        'label' => 'Blood Amount',
                        'value' => $model->blood_amount.' Units',
                    ],
                    'paid_amount',
                    [
                        'label' => 'Coordinates',
                        'value' => $model->lat_long,
                    ],
                    'location_name',
                    'full_address:ntext',
                    'reason',
                    'postal_code',
                    'created',
                    [ 'format'=>'html','label' => 'Token', 'value' => '<code>#'.$model->req_key.'</code>'],
                    [ 'format'=>'html', 'label' => 'Status', 'value' => '<code><i>'.$model->getRequestStatus($model->status).'</i></code>'],
                    //'status',
                ],
            ]) ?>
        </div>
    <!--     <div class="panel-footer" style="text-align: right">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
        </div> -->
    </div>

</div>