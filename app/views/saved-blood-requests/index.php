<?php

use kartik\tabs\TabsX;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SavedBloodRequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Saved Blood Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saved-blood-requests-index">

    <?php
    $content = GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover'=>true,
        'showOnEmpty' => false,
        'summary' => '',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn', 'header' => ''],
            //'saved_id',
            //'request_id',
            //'saved_by',

            [
                'attribute' => 'request_id',
                'format' => 'html',
                'value' => function ($model){
                    return '<code>'.$model->request->req_key.'</code>';
                }
            ],
            [
                'attribute' => 'saved_by',
                'headerOptions' => ['width' => '100px'],
                'value' => function ($model){
                    return $model->savedBy->username;
                }
            ],
            [
                'attribute' => 'request_id',
                'label' => 'BloodGroup',
                'headerOptions' => ['width' => '50px'],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model){
                    return $model->request->bloodGroup->blood_name;
                }
            ],
            ['attribute' => 'request_id',
                'label' => 'Amounts',
                'headerOptions' => ['width' => '50px'],
                'value' => function ($model) {
                    return $model->request->blood_amount . ' Units';
                },
            ],
            [
                'attribute' => 'request_id',
                'label' => 'Location',
                'value' => function ($model){
                    return $model->request->location_name;
                }
            ],
            [
                'attribute' => 'saved_date',
                'headerOptions' => ['width' => '140px'],
            ],
            ['format' => 'html',
                'attribute' => 'request_id',
                'label' => 'Status',
                'headerOptions' => ['width' => '150px'],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    return '<code><i>' . $model->getRequestStatus($model->request->status) . '</i></code>';
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'cursor:default; color:#428bca;'],
            ],
        ],
        'resizableColumns'=>false,
        'responsiveWrap' => false,
    ]);
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-save"></i>&nbsp;<?= $this->title; ?></h3>
        </div>
        <div class="panel-body">
            <?php
            $items = [
                [
                    'label'=>'<i class="fa fa-refresh fa-spin"></i>&nbsp; Saved Requests',
                    'content'=>$content,
                    'active'=>true,
                ],
                ['label'=>'<i class="fa fa-plus"></i>&nbsp; New Request', 'url' => Url::to(['create'])]
            ];

            echo TabsX::widget([
                'items'=>$items,
                'position'=>TabsX::POS_ABOVE,
                'align' => TabsX::ALIGN_LEFT,
                'bordered'=>true,
                'encodeLabels'=>false
            ]);

            ?>
        </div>
    </div>

</div>
