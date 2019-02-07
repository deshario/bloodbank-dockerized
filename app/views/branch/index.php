<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use dosamigos\google\maps\MapAsset;

MapAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branches';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(".modal-content{border-radius:5px;}");

$this->registerJs("
    $(function() {
     $('.view_branch').click(function(event) {
         event.preventDefault();
         var title = this.getAttribute('modal-title');
         $.get($(this).attr('href'), function(data) {
             $('#branchModal #modal_title').html(title);
             $('#branchModal #modalContent').html(data);
             $('#branchModal').modal('show')
         });
         return false;
     });
 });
");

?>

<?php
Modal::begin([
    'header' => '<span class="fa fa-hospital-o fa-lg"></span>&nbsp; <a id="modal_title" style="color: white; font-size: 18px;"></a>',
    'id' => 'branchModal',
    'headerOptions' => ['class' => 'modal-header modal-header-primary'],
    'size' => 'modal-md',
]);
echo "<div id='modalContent'></div>";

Modal::end();
?>
<!---->
<?php
//list($latitude, $longitude) = explode(',', '18.794372745745633,100.7683641268311');
//$coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);
//$map = new Map([
//    'center' => $coord,
//    'zoom' => 16,
//    'width' => '100%',
//    'height' => '200',
//    //'mapTypeId' => \dosamigos\google\maps\MapTypeId::SATELLITE,
//]);
//$marker = new Marker([
//    'position' => $coord,
//    'title' => 'ee',
//]);
//// Provide a shared InfoWindow to the marker
//$marker->attachInfoWindow(
//    new InfoWindow([
//        'content' => 'ww'
//    ])
//);
//// Add marker to the map
//$map->addOverlay($marker);
//echo $map->display();
//?>

<div class="branch-index">
    <!--    $model->getManagers()->count()-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="fa fa-hospital-o fa-lg"></span>&nbsp; Branches</h3>
        </div>
        <div class="panel-body" style="margin: -8px -8px -25px -8px"> <!-- top right bottom left -->

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn', 'header' => '', 'contentOptions' => ['style'=>'vertical-align: middle;']],

                    //'branch_id',
                    ['attribute' => 'branch_name',
                        'headerOptions' => ['width' => '200px'],
                        'vAlign' => GridView::ALIGN_CENTER,
                        'contentOptions' => ['style'=>'vertical-align: middle;']
                    ],
                    //'branch_lat_long',
//                        'branch_address:ntext',
                    [
                        'format' => 'html',
                        'vAlign' => GridView::ALIGN_CENTER,
                        'contentOptions' => ['class' => 'text-left'],
                        'attribute' => 'branch_address',
//                            'contentOptions' => ['style'=>'padding:0px 0px 0px 30px;vertical-align: middle;'],
//                            'contentOptions' => ['class' => 'text-center'],
//                            'vAlign' => GridView::ALIGN_MIDDLE,
//                            'headerOptions' => ['width' => '500px'],
                    ],
                    //branch_code,
                    //'branch_created',
                    ['class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['width' => '100px', 'style' => 'cursor:default; color:#428bca;'],
                        'template' => '{view}',   //{view}&nbsp;
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a("<span class='fa fa-search-plus'></span> View",
                                    ['view', 'id' => $model->branch_id], [
                                        'class' => 'btn btn-sm btn-flat btn-primary', // view_branch
                                        //'title' => 'View',
                                        //'data-toggle' => 'tooltip',
                                        //'data-placement' => 'top',
                                        'modal-title' => $model->branch_name,
                                    ]);
                            },
//                            'update' => function ($url, $model) {
//                                return Html::a('<button class="btn btn-xs btn-success" data-toggle="tooltip"
//                                data-placement="top" title="Update">
//                                        <i class="fa fa-edit"></i></button>', $url
//                                );
//                            },
//                            'delete' => function ($url, $model) {
//                                return Html::a('<button class="btn btn-xs btn-danger" data-toggle="tooltip"
//                                data-placement="top" title="Delete">
//                                        <i class="fa fa-trash-o"></i></button>', $url
//                                );
//                            },
                        ],
//                        'urlCreator' => function ($action, $model, $key, $index) {
//                            if ($action == 'update') {
//                                $url = Url::toRoute(['update', 'id' => $model->branch_id]);
//                                return $url;
//                            }
//                            if ($action == 'delete') {
//                                $url = Url::toRoute(['delete', 'id' => $model->branch_id]);
//                                return $url;
//                            }
//                        }
                    ],
//                        ['class' => 'yii\grid\ActionColumn'],
                ],
                'hover' => true,
                'bordered' => true,
                'striped' => true,
                'showOnEmpty' => false,
                'summary' => '',
                'resizableColumns' => false,
                'responsiveWrap' => false,
            ]); ?>

        </div>
    </div>
</div>