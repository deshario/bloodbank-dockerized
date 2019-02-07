<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use kartik\tabs\TabsX;
use yii\helpers\Url;

$this->title = 'Blood Requests Verifications';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
    'https://js.pusher.com/4.1/pusher.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$root = Yii::getAlias('@web');

//$this->registerJs("
//    Pusher.logToConsole = true;
//     var pusher = new Pusher('c93938b99ff99b4b5134', {
//      cluster: 'ap1',
//      encrypted: true
//    });
//
//    var channel = pusher.subscribe('main-channel');
//
//    channel.bind('verification-event', function(data) {
//         var staff_token = data.staff_token;
//         var donation_id = parseInt(data.donate_id);
//         var donated_to = data.donated_to;
//         var u_id = data.u_id;
//         $('#modal').modal('show')
//         $('#modalContent').load('$root/FCM/Verification.php?donation_id='+donation_id+'&&staff_token='+staff_token)
//    });
//
//");
?>
<?php
    $incoming_verifications = GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'hover'=>true,
    'showOnEmpty' => false,
    'summary' => '',
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '',],

        //'donate_id',
//        'request_id',
//        'donated_by',
//        'donated_to',
//        'manager_id',
//        'verified',
        //'donated_date',
        [
            'attribute' => 'donated_to',
            'label' => 'Donor',
            'format' => 'html',
            'value' => function ($model){
                return "<a href='' data-toggle='tooltip' data-placement='top' title='".$model->donated_by."'>".$model->donatedBy->username."</a>";
            }
        ],
        [
            'attribute' => 'donated_to',
            'label' => 'Receiver',
            'value' => function ($model) {
                return $model->donatedTo->username;
            }
        ],
        [
            'attribute' => 'donate_id',
            'label' => 'BloodGroup',
            'headerOptions' => ['width' => '80px'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model){
                return $model->request->bloodGroup->blood_name;
            }
        ],
        [   'attribute' => 'donate_id',
            'label' => 'Amounts',
            'headerOptions' => ['width' => '50px'],
            'value' => function ($model){
                return $model->request->blood_amount.' Units';
            },
        ],
        [   'attribute' => 'donate_id',
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
            'headerOptions' => ['width' => '150px'],
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
        //'verified'
        //'manager_id',
        //'donated_date:datetime',
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
                        ['data-confirm' => 'Are you sure you want to deny this verification ?<br><code>Denying verification will delete this verification from system !</code>', 'data-method' =>'POST']
                    );
                },
            ],
            'urlCreator' => function($action, $model, $key, $index) {
                if ($action == 'details') {
                    $url = Url::toRoute(['blood-requests-verification/view', 'id' => $model->donate_id]);
                    return $url;
                }
                if ($action == 'approve'){
                    $url = Url::toRoute(['blood-requests-verification/verify', 'id' => $model->donate_id]);
                    return $url;
                }
                if ($action == 'deny') {
                    $url = Url::toRoute(['blood-requests-verification/deny', 'id' => $model->donate_id]);
                    return $url;
                }
            }
        ],

    ],
    'resizableColumns'=>true,
    'responsiveWrap' => false,
]);
?>

<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-users"></i>&nbsp;<?= $this->title; ?></h3>
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

