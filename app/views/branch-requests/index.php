<?php

use kartik\tabs\TabsX;
use yii\bootstrap\Modal;use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\google\maps\MapAsset;
use app\models\BranchRequests;

MapAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\BranchRequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branch Requests';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss(".modal-content{border-radius:5px;}");

?>
<div class="branch-requests-index">

    <?php $Mcontent = GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn', 'header' => '',],
            //'req_id',
            //'branch.branch_name',
            ['attribute' => 'branch_id', 'label' => 'Branch Name',
                'label' => '<span class="fa fa-hospital-o"></span>&nbsp; Branch Name' ,
                'encodeLabel' => false,
                'filter'=>\app\models\BranchRequests::getBranchList(),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => 'Select Branch',
                ],
                'value' => function ($model) {
                    return $model->branch->branch_name;
                },
            ],
//            ['attribute' => 'branch_id', 'label' => 'Branch Name',
//                'value' => function ($model) {
//                    return $model->branch->branch_code;
//                },
//            ],
            ['attribute' => 'blood_group', 'label' => 'Blood Group',
                'label' => '<span class="fa fa-tint"></span>&nbsp; Group' ,
                'encodeLabel' => false,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['width' => '80px'],
                'filter'=>\app\models\BranchRequests::getBloodgroups(),
                'value' => function ($model) {
                    return $model->bloodGroup->blood_name;
                },
            ],
            ['attribute' => 'blood_amount',
                'label' => 'Requried',
                'headerOptions' => ['width' => '80px'],
                'value' => function ($model) {
                    if ($model->blood_amount == '') {
                        return null;
                    }
                    return $model->blood_amount . ' Units';
                },
            ],
            ['attribute' => 'paid_amount',
                'label' => 'Paid',
                'headerOptions' => ['width' => '80px'],
                'value' => function ($model) {
                    if ($model->paid_amount == '') {
                        return null;
                    }
                    return $model->paid_amount . ' Units';
                },
            ],
            //[ 'attribute' => 'created', 'label' => '<span class="fa fa-calendar"></span>&nbsp; Created', 'encodeLabel' => false,],
            //'created',
            [ 'format' => 'html','attribute' => 'created',
                'label' => '<span class="fa fa-calendar"></span>&nbsp; Created',
                'encodeLabel' => false,
                'contentOptions' => ['width' => '130px'],
                'value' => function ($model){
                    return $model->getCustomDate($model->created, BranchRequests::DATE_MEDIUM,true);
                }
                ],
            ['format' => 'html',
                'attribute' => 'status',
                'headerOptions' => ['width' => '80px'],
                //'contentOptions' => ['class' => 'text-center'],
                'filter' => ["1" => "OPENING", "0" => "CLOSED"],
//                'filterInputOptions' => [
//                    'class' => 'form-control',
//                    'prompt' => 'Select',
//                    //'placeholder' => 'Request Status'
//                ],
                'value' => function ($model) {
                    if ($model->status == 1) {
                        return '<a class="label label-success"><span class="fa fa-spinner fa-spin"></span>&nbsp; '.$model->getRequestStatus($model->status). '</a>';
                    } else {
                        return '<a class="label label-danger"><span class="fa fa-close"></span>&nbsp; '.$model->getRequestStatus($model->status). '</a>';
                    }
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['width' => '100px','style' => 'cursor:default; color:#428bca;'],
                'template' => '{view}&nbsp{update}',   //{view}&nbsp;
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('<button class="btn btn-xs btn-primary primary-tooltip" data-toggle="tooltip"
                                data-placement="top" title="View Request"><i class="fa fa-search-plus"></i> </button>', $url
                        );
                    },
                    'update' => function($url, $model){
                        return Html::a(' <button class="btn btn-xs btn-success success-tooltip" id="reload" data-toggle="tooltip"
                                data-placement="top" title="Update Request"><i class="fa fa-edit"></i> </button>',$url
                           // ['data-confirm' => 'Please insure that this request is true ! <br>', 'data-method' =>'POST']
                        );
                    },
                ],
                ]
        ],
        'hover' => true,
        'showOnEmpty' => true,
        'summary' => '',
        'resizableColumns' => false,
        'responsiveWrap' => false,
//        'panel' => [
//            'heading' => '<h3 class="panel-title"><i class="fa fa-desktop"></i>&nbsp;&nbsp; Branch Requests</h3>',
//            'type' => 'default',
//            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> New Request', ['create'], ['class' => 'pull-right btn btn-default', 'style' => 'margin-right:5px']),
//            //'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
//            'after' => false,
//            'footer' => false
//        ],
        'export' => false,
    ]); ?>

</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-desktop"></i>&nbsp;<?= $this->title; ?></h3>
    </div>
    <div class="panel-body">
        <?php
        $items = [
                //['label' => 'haha', 'content' => 'deom content', 'active' => 'true'],

            [
                'label'=>'<i class="fa fa-check-square-o"></i>&nbsp; ALL',
                'content' => $Mcontent,
                'active' => 'true',
            ],
            [
                'label'=>'<i class="fa fa-envelope-o"></i>&nbsp; OPENING',
                'linkOptions' => ['data-url' => Url::to(['opening'])],
            ],
            ['label'=>'<i class="fa fa-plus"></i>&nbsp; Create Request', 'url' => Url::to(['create'])]
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