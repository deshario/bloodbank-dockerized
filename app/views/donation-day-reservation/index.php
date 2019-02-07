<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DonationDayReservationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Donation Day Reservation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="donation-day-reservation-index">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="fa fa-hospital-o fa-lg"></span>&nbsp; <?=$this->title;?></h3>
        </div>
        <div class="panel-body" style="margin: -8px -8px -25px -8px">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'hover' => true,
                'bordered' => true,
                'striped' => true,
                'showOnEmpty' => false,
                'summary' => '',
                'resizableColumns' => false,
                'responsiveWrap' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn', 'header' => '', 'contentOptions' => ['style'=>'vertical-align: middle;']],

                    //'reserved_id',
                    //'user_id',
                    //'branch_id',

                    ['attribute' => 'user_id',
                        'value' => function ($model) {
                            return $model->user->username;
                        },
                    ],
                    ['attribute' => 'branch_id',
                        'value' => function ($model) {
                            return $model->branch->branch_name;
                        },
                    ],
                    ['attribute' => 'reservation_key',
                        'format' => 'html',
                        'value' => function ($model) {
                            return '<code>'.$model->reservation_key.'</code>';
                        },
                    ],
                    'reserved_date',
                    'user_notes',

                    ['class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'headerOptions' => ['width' => '100px', 'style' => 'cursor:default; color:#428bca;'],
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
