<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Tabs;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BloodRequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blood Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blood-requests-index">

    <?php Pjax::begin(); ?>

    <?php
    // 'req_id',
    //            'requester_id',
    //            'donater_id',
    //            'blood_group',
    //            'blood_amount',
    //            //'paid_amount',
    //            //'lat_long',
    //            //'full_address:ntext',
    //            //'reason',
    //            //'postal_code',
    //            //'created',
    //            //'req_key',
    //            //'status',
    Pjax::end(); ?>
</div>

<!-- Sample REST API CREATE REQUEST
<?= Html::a('Link Text', ['create_request'], [
    'data' => [
        'method' => 'post',
        'confirm' => 'Are you sure?',
        'params' => [
            'requester_id' => '1',
            'blood_group' => '4',
            'blood_amount' => '10',
            'lat_long' => '100,200',
            'full_address' => 'Mero Hospital',
            'reason' => 'Sick',
            'postal_code' => '55000',
        ],
    ]
]) ?>
-->

<?php Pjax::begin(['id' => 'grid']);

$content = GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'hover' => true,
    'showOnEmpty' => false,
    'summary' => '',
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '',],
        [
            'attribute' => 'requester_id',
            'label' => 'Requester',
            'value' => function ($model) {
                //return $model->getEncryptedKey($model->getRequester($model->requester_id));
                return $model->requester->username;
            },
            'vAlign' => GridView::ALIGN_LEFT,
        ],
        [
            'attribute' => 'blood_group',
            'contentOptions' => ['class' => 'text-center'],
            'vAlign' => GridView::ALIGN_MIDDLE,
            'headerOptions' => ['width' => '50px'],
            'value' => function ($model) {
                return $model->bloodGroup->blood_name;
            },
        ],
        ['attribute' => 'blood_amount',
            'label' => 'Amounts',
            'headerOptions' => ['width' => '50px'],
            'value' => function ($model) {
                return $model->blood_amount . ' Units';
            },
        ],
        //['attribute' => 'paid_amount'],
//        [
//                'attribute' => 'paid_amount',
//                'footer' => \app\models\BloodRequests::getTotal($dataProvider->models, 'blood_amount')
//        ],
        [
            'attribute' => 'created',
            'label' => 'Datetime',
            'format' => 'html',
            'value' => function ($model) {
                return $model->getCustomDate($model->created, \app\models\BranchRequests::DATE_MEDIUM, true);
            },
            'contentOptions' => ['width' => '130px'],
            'vAlign' => GridView::ALIGN_MIDDLE,
        ],
        [
            'format' => 'html',
            'attribute' => 'req_key',
            'label' => 'Token',
            'headerOptions' => ['width' => '150px'],
            'value' => function ($model) {
                return '<code><i>#' . $model->req_key . '</i></code>';
            },
        ],
        ['format' => 'html',
            'attribute' => 'status',
            'headerOptions' => ['width' => '100px'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return '<code><i>' . $model->getRequestStatus($model->status) . '</i></code>';
            },
        ],
        //['class' => 'yii\grid\ActionColumn'],
        ['class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'headerOptions' => ['width' => '100px', 'style' => 'cursor:default; color:#428bca;'],
            'template' => '{details}&nbsp{approve}&nbsp{deny}',   //{view}&nbsp;
            'buttons' => [
                'details' => function ($url, $model) {
                    return Html::a('<button class="btn btn-xs btn-primary primary-tooltip" data-toggle="tooltip"
                                data-placement="top" title="View Details"><i class="fa fa-search-plus"></i> </button>', $url
                    );
                },
                'approve' => function ($url, $model) {
                    return Html::a('<button class="btn btn-xs btn-success success-tooltip" id="reload" data-toggle="tooltip"
                                data-placement="top" title="Approve Request"><i class="fa fa-check"></i> </button>', $url,
                        ['data-confirm' => 'Please insure that this request is true ! <br>', 'data-method' => 'POST']
                    );
                },
                'deny' => function ($url, $model) {
                    return Html::a('<button class="btn btn-xs btn-danger danger-tooltip" data-toggle="tooltip"
                                data-placement="top" title="Deny Request"><i class="fa fa-close"></i> </button>', $url,
                        ['data-confirm' => 'Are you sure you want to deny this request ?<br><code>Denying Request will delete request from system !</code>', 'data-method' => 'POST']
                    );
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action == 'details') {
                    $url = Url::toRoute(['blood-requests/view',
                        'id' => $model->id,
                        //'key' => $model->req_key,
                    ]);
                    return $url;
                }
                if ($action == 'approve') {
                    $url = Url::toRoute(['blood-requests/accept',
                        'id' => $model->id,
                        'staffname' => Yii::$app->user->identity->username
                    ]);
                    return $url;
                }
                if ($action == 'deny') {
                    $url = Url::toRoute(['blood-requests/deny', 'id' => $model->id]);
                    return $url;
                }
            }
        ],

    ],
    'resizableColumns' => false,
    'responsiveWrap' => false,
]);

Pjax::end();
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-users"></i>&nbsp;<?= $this->title; ?></h3>
    </div>
    <div class="panel-body">
        <?php
        $items = [
            [
                'label' => '<i class="fa fa-envelope-o"></i>&nbsp; Incoming',
                'content' => $content,
                'active' => true,
                'linkOptions' => ['data-url' => Url::to(['incoming'])],
            ],
            [
                'label' => '<i class="fa fa-refresh fa-spin"></i>&nbsp; Approved',
                'linkOptions' => ['data-url' => Url::to(['approved'])],
            ],
            [
                'label' => '<i class="fa fa-check-square-o"></i>&nbsp; Received',
                'linkOptions' => ['data-url' => Url::to(['blood-requests-verification/completed'])],
            ],
            ['label'=>'<i class="fa fa-plus"></i>&nbsp; Create Request', 'url' => Url::to(['create'])]
        ];

        echo TabsX::widget([
            'items' => $items,
            'position' => TabsX::POS_ABOVE,
            'align' => TabsX::ALIGN_LEFT,
            'bordered' => true,
            'encodeLabels' => false
        ]);

        ?>
    </div>
</div>
