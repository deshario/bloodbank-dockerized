<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\BranchRequests;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CampaignsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campaigns';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
    .Mshadow{
        -webkit-box-shadow: 3px 3px 5px 6px #ccc;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
        -moz-box-shadow:    3px 3px 5px 6px #ccc;  /* Firefox 3.5 - 3.6 */
        box-shadow:         3px 3px 5px 6px #ccc;
    }
");
?>
<div class="campaigns-index">

    <!--
    <div class="alert panel panel-default alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        &nbsp; Campaigns is a tool that helps to find more donors. &nbsp;
        <strong>
            <?php
    echo Html::a("<span class='fa fa-plus fa-lg'></span>", ['create'],
        [
            'class' => 'btn btn-xs btn-primary',
            'style' => 'text-decoration : none',
            'title' => 'Create New Campaign',
            'data-toggle' => 'tooltip',
            'data-placement' => 'right',
        ]);
    ?>
        </strong>
    </div>
    -->

    <div class="row">
        <?php
        foreach ($dataProvider->models as $model) {
            $date = $model->campaign_created;
            $date = BranchRequests::getCustomDate($model->campaign_created, BranchRequests::DATE_MEDIUM,true);
            //$creator = $model->campaignCreator->username;
            ?>

            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><span class="fa fa-bell-o"></span>&nbsp; <?= $model->campaign_name; ?></h3>
                    </div>
                    <div class="box-body">
                        <!-- <img class="img-responsive" src="http://via.placeholder.com/700x400"/> -->
                        <img class="img-responsive" src="<?= Yii::$app->request->baseUrl.'/uploads/campaigns/'.$model->campaign_img;?>" style="
                        max-height: 145px; max-width: 100%;"/>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-default pull-left"><span class="fa fa-calendar-o"></span>&nbsp; <?= $date; ?></a>
                        <?= Html::a("<span class='fa fa-search-plus'></span> View",
                            ['view', 'id'=> $model->campaign_id],
                            ['class' => 'btn btn-default pull-right',]
                        ); ?>
                    </div>
                </div>
            </div>

        <?php } ?>

    </div>

    <!--
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'campaign_id',
            'campaign_name',
            'campaign_desc',
            'campaign_img',
            'campaign_created',
            //'campaign_coordinates',
            //'campaign_address:ntext',
            //'campaign_key',
            //'campaign_creator',
            //'campaign_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    -->

</div>
