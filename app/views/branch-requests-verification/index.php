<?php

use kartik\tabs\TabsX;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BranchRequestsVerificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branch Requests Verification';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-requests-verification-index">

    <?php
    $incoming_verifications = GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover'=>true,
        'showOnEmpty' => false,
        'summary' => '',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn', 'header' => '',],

           // 'donate_id',
//            'branch_requests_id',
//            'donor_id',
//            'verified',
//            'donated_date',
            [
                'attribute' => 'donor_id',
                'label' => 'Donor',
                'format' => 'html',
                'value' => function ($model){
                    return "<a href='' data-toggle='tooltip' data-placement='top' title='".$model->donor_id."'>".$model->donor->username."</a>";
                }
            ],
            [
                'attribute' => 'branch_requests_id',
                'label' => 'Receiver',
                'value' => function ($model) {
                    return $model->branchRequests->branch->branch_name;
                }
            ],
            [
                'attribute' => 'branch_requests_id',
                'label' => 'BloodGroup',
                'headerOptions' => ['width' => '80px'],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model){
                    return $model->branchRequests->bloodGroup->blood_name;
                }
            ],
            [   'attribute' => 'branch_requests_id',
                'label' => 'Amounts',
                'headerOptions' => ['width' => '50px'],
                'value' => function ($model){
                    return $model->branchRequests->blood_amount.' Units';
                },
            ],
            [   'attribute' => 'branch_requests_id',
                'label' => 'Paid',
                'headerOptions' => ['width' => '80px'],
                'value' => function ($model){
                    return $model->paid_amount.' Units';
                },
            ],
            [
                'attribute' => 'donated_date',
                'label' => 'Donated Date',
                'format'=>'html',
                'value' => function ($model) {
                    return $model->getCustomDate($model->donated_date);
                },
                'headerOptions' => ['width' => '180px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [   'format' => 'html',
                'attribute' => 'verified',
                'label' => 'Status',
                'headerOptions' => ['width' => '100px'],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model){
                    return '<code><i>'.$model->getDonationStatus($model->verified).'</i></code>';
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'cursor:default; color:#428bca;'],
                'template' => '{details}&nbsp{approve}&nbsp{deny}',   //{view}&nbsp;
                'buttons' => [
                    'details' => function($url, $model) {
                        return Html::a('<button class="btn btn-xs btn-primary primary-tooltip" data-toggle="tooltip"
                                data-placement="top" title="View Details"><i class="fa fa-search-plus"></i> </button>', $url
                        );
                    },
                    'approve' => function($url, $model){
                        return Html::a('<button class="btn btn-xs btn-success success-tooltip" id="reload" data-toggle="tooltip" 
                                data-placement="top" title="Verify Donation"><i class="fa fa-check"></i></button>',$url,
                            ['data-confirm' => 'Please insure that this verification is true ! <br>', 'data-method' =>'POST']
                        );
                    },
                    'deny' => function($url, $model) {
                        return Html::a('<button class="btn btn-xs btn-danger danger-tooltip" data-toggle="tooltip" 
                                data-placement="top" title="Deny Verification"><i class="fa fa-close"></i></button>', $url,
                            ['data-confirm' => 'Are you sure you want to deny this verification ?<br><code>Denying Verification will delete it from system !</code>', 'data-method' =>'POST']
                        );
                    },
                ],
                'urlCreator' => function($action, $model, $key, $index) {
                    if ($action == 'details') {
                        $url = Url::toRoute(['branch-requests-verification/view', 'id' => $model->donate_id]);
                        return $url;
                    }
                    if ($action == 'approve'){
                        $url = Url::toRoute(['branch-requests-verification/verify', 'id' => $model->donate_id,
                        ]);
                        return $url;
                    }
                    if ($action == 'deny') {
                        $url = Url::toRoute(['branch-requests-verification/deny', 'id' => $model->donate_id]);
                        return $url;
                    }
                }
            ],

        ],
//        'panel' => [
//            'heading'=>false,
//            'type'=>'default',
//            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
//            'after'=>false,
//            'footer'=>false
//        ],
        'resizableColumns'=>false,
        'responsiveWrap' => false,
    ]);
    ?>


    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-desktop"></i>&nbsp;<?= $this->title; ?></h3>
            </div>
            <div class="panel-body">
                <?php
                $items = [
                    [
                        'label'=>'<i class="fa fa-refresh fa-spin"></i>&nbsp; Incoming Verifications',
                        'content'=>$incoming_verifications,
                        'active'=>true,
                        'linkOptions' => ['data-url' => Url::to(['pending'])],
                    ],
//                [
//                    'label'=>'<i class="fa fa-barcode"></i> QR-Code Verification',
//                    //'content'=>$verify_new,
//                ],
                    [
                        'label'=>'<i class="fa fa-check-square-o"></i>&nbsp; Verified Donations',
                        'linkOptions' => ['data-url' => Url::to(['completed'])],
                    ],
                    ['label'=>'<i class="fa fa-plus"></i>&nbsp; Create Verification', 'url' => Url::to(['create'])]
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

</div>
