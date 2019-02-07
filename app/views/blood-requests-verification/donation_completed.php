<?php
/**
 * Created by PhpStorm.
 * Yii2User: Deshario
 * Date: 10/29/2017
 * Time: 6:01 PM
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use kartik\tabs\TabsX;
use yii\helpers\Url;

$this->title = 'Verifications';
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
<?= GridView::widget([
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
                return "<a data-toggle='tooltip' data-placement='bottom' title='".$model->donated_by."'>".$model->donatedBy->username."</a>";
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
            'headerOptions' => ['width' => '180px'],
            'contentOptions' => ['class' => 'text-center'],
        ],
//        [
//            'format' => 'html',
//            'attribute' => 'donate_id',
//            'label' => 'Token',
//            'headerOptions' => ['width' => '150px'],
//            'value' => function ($model){
//                return '<code><i>#'.$model->request->req_key.'</i></code>';
//            },
//        ],
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
            'visible' => ($showViewColumn == 'show'), // visible if value == show
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['width' => '100px', 'style' => 'cursor:default; color:#428bca;'],
            'template' => '{details}',   //{view}&nbsp;
            'buttons' => [
                'details' => function($url, $model) {
                    return Html::a('<button class="btn btn-xs btn-primary primary-tooltip" data-toggle="tooltip"
                                data-placement="top" title="View Details"><i class="fa fa-search-plus"></i>&nbsp; View</button>', $url
                    );
                },
            ],
            'urlCreator' => function($action, $model, $key, $index) {
                if ($action == 'details') {
                    $url = Url::toRoute(['blood-requests-verification/view','id' => $model->donate_id]);
                    return $url;
                }
            }
        ],

        ['class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'visible' => ($showActionColumn == 'show'), // visible if value == show
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
    'resizableColumns'=>false,
    'responsiveWrap' => false,
]); ?>