<?php

use yii\helpers\Html;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use app\models\BranchRequests;
use dosamigos\google\maps\MapAsset;

MapAsset::register($this);

$this->title = 'Branch Requests';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <?php
    foreach ($dataProvider->models as $model) {
    $registered = $model->created;
    $temp_date = strtotime('-0 day', strtotime($registered));
    $repaired_date = date('Y-m-j', $temp_date);
    ?>

    <div class="col-md-3 col-xs-12">
        <?php if ($model->status == BranchRequests::REQUEST_OPEN){ ?>
        <div class="box box-success" data-widget="box-widget">
            <?php }else{ ?>
            <div class="box box-danger" data-widget="box-widget">
                <?php } ?>
                <div class="box-header with-border">
                    <a style="overflow: hidden; width:280px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; font-family: 'Maven Pro', sans-serif; text-transform: capitalize;">
                        <span class="fa fa-h-square"></span> <?php echo $model->branch->branch_code ?> </a>
                    <div class="box-tools pull-right" style="margin-top:2px">
                        <a class="btn btn-xs btn-success"><span
                                class="fa fa-calendar-o"></span>&nbsp; <?= Yii::$app->formatter->asDate($repaired_date, 'medium'); ?>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div style="margin: -5px;">
                        <?php
                        list($latitude, $longitude) = explode(',', $model->branch->branch_lat_long);
                        $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);
                        $map = new Map([
                            'center' => $coord,
                            'zoom' => 16,
                            'width' => '100%',
                            'height' => '200',
                        ]);
                        $marker = new Marker([
                            'position' => $coord,
                            'title' => $model->branch->branch_name,
                        ]);
                        // Provide a shared InfoWindow to the marker
                        $marker->attachInfoWindow(
                            new InfoWindow([
                                'content' => $model->branch->branch_name
                            ])
                        );
                        // Add marker to the map
                        $map->addOverlay($marker);
                        echo $map->display();
                        ?>
                    </div>
                </div>

                <div class="box-footer" align="right">

               <!--
                    <?php if($model->status == 1){ ?>
                        <a class="btn btn-xs btn-success pull-left"><span
                                class="fa fa-spinner fa-spin"></span>&nbsp; <?php echo $model->getRequestStatus($model->status); ?></a>
                    <?php }else{?>
                        <a class="btn btn-xs btn-danger pull-left"><span
                                class="fa fa-close"></span>&nbsp; <?php echo $model->getRequestStatus($model->status); ?></a>
                    <?php } ?>
                    -->

                    <a class="btn btn-xs btn-danger pull-left"><span
                            class="fa fa-tint"></span>&nbsp
                        <?php
                        $required = $model->blood_amount;
                        $payed = $model->paid_amount;
                        $remain = $required-$payed;
                        echo "Required ".$remain; ?>
                        Units</a>

                    <?= Html::a("<span class='fa fa-search'></span>&nbsp; View",
                        ['view', 'id' => $model->req_id],
                        ['class' => 'btn btn-xs btn-primary',]) ?>

                </div>

            </div>
        </div>

        <?php } ?>
    </div>