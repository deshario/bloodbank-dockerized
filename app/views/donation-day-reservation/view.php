<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->branch->branch_name;
$this->params['breadcrumbs'][] = ['label' => 'Donation Day Reservations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
    .Mshadow{
        -webkit-box-shadow: 3px 3px 5px 6px #ccc;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
        -moz-box-shadow:    3px 3px 5px 6px #ccc;  /* Firefox 3.5 - 3.6 */
        box-shadow:         3px 3px 5px 6px #ccc;
    }
");
?>
<div class="donation-day-reservation-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><?= $this->title; ?></h3>
        </div>
        <div class="panel-body">

            <div style="margin-top: -5px; margin-bottom: 10px;" class="Mshadow">
                <?php
                list($latitude, $longitude) = explode(',', $model->branch->branch_lat_long);
                $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);
                $map = new Map([
                    'center' => $coord,
                    'zoom' => 16,
                    'width' => '100%',
                    'height' => '300',
                    //'mapTypeId' => \dosamigos\google\maps\MapTypeId::SATELLITE,
                ]);
                $marker = new Marker([
                    'position' => $coord,
                    'title' => $model->branch->branch_name,
                ]);
                // Provide a shared InfoWindow to the marker
                $marker->attachInfoWindow(
                    new InfoWindow([
                        'content' => "<strong>" . $model->branch->branch_name . "</strong><br><br><a href='#'>View on Google Maps</a>"
                    ])
                );
                $map->addOverlay($marker);
                echo $map->display();
                ?>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'reserved_id',
                    ['attribute' => 'user_id', 'value' => $model->user->username,],
                    ['attribute' => 'branch_id', 'value' => $model->branch->branch_name,],
                    'user_notes',
                    ['attribute' => 'reservation_key', 'format' => 'html', 'value' => '<code>'.$model->reservation_key.'</code>',],
                    'reserved_date',
                ],
            ]) ?>

        </div>
    </div>

</div>
