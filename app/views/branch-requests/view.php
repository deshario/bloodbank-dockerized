<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\BranchRequests;


/* @var $this yii\web\View */
/* @var $model app\models\BranchRequests */

$this->title = $model->branch->branch_code.'#'.$model->req_id;
$this->params['breadcrumbs'][] = ['label' => 'Branch Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-requests-view">
    <!--
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->req_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->req_id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
    </p>
    -->

    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><span class="fa fa-hospital-o fa-lg"></span>&nbsp; <?= $model->branch->branch_name; ?></h3>
            <div class="box-tools">
                <?php echo Html::a("Update",
                    ['update', 'id' => $model->req_id], [
                        'class' => 'btn btn-xs btn-danger',
                        'style' => 'margin-top:2px',
                       // 'title' => 'Update',
                        //'data-toggle' => 'tooltip',
                        //'data-placement' => 'top',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?php
            list($latitude, $longitude) = explode(',', $model->branch->branch_lat_long);
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
                'title' => $model->branch->branch_name,
            ]);
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => $model->branch->branch_address,
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
                    //'req_id',
                    //'branch_id',
                    ['label' => 'Branch Name', 'value' => $model->branch->branch_name,],
                    ['format' => 'html', 'label' => 'Branch Code', 'value' => '<code><i>' . $model->branch->branch_code . '</i></code>',],
                    ['label' => 'Blood Group', 'value' => $model->bloodGroup->blood_name,],
                    [
                        'label' => 'Blood Amount',
                        //'value' => $model->blood_amount . ' Units',
                        'value' => $model->getCorrectAmount($model->blood_amount),
                    ],
                    [
                        'label' => 'Paid Amount',
                        'value' => $model->getCorrectAmount($model->paid_amount),
                    ],
                    //'blood_group',
                    [
                        'label' => 'Created',
                        //'value' => Yii::$app->formatter->asDate($model->created, 'medium')
                        'value' => $model->getCustomDate($model->created, BranchRequests::DATE_MEDIUM)
                    ],
                    [ 'format'=>'html','label' => 'Request Key', 'value' => '<code>#'.$model->req_key.'</code>'],
                    //'created',
                    //'status',
                    ['format' => 'html', 'label' => 'Status', 'value' => '<code><i>' . $model->getRequestStatus($model->status) . '</i></code>'],
                ],
            ]) ?>

        </div>
    </div>

</div>
