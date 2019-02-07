<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Managers;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ManagersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Managers';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
.bg{
       background: rgba(248, 248, 248, 0.8);
       border-radius: 5px
       }");

$this->registerJs("
$(function(){ 
    $('.view_details').click(function (){
        $.get($(this).attr('href'), function(data) {
          $('#view_managers').modal('show')
          .find('#managers_content')
          .html(data)
       });
       return false;
    }); 
});
");
?>

<?php
Modal::begin([
    'header' => 'Title',
    'id' => 'view_managers',
    'size' => 'modal-md',
]);
echo "<div id='managers_content'></div>";
Modal::end();
?>

<div class="managers-index">
    <!-- <?= GridView::widget([
        'krajeeDialogSettings' => [ 'options' => ['title' => 'Your Custom Title'] ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Countries</h3>',
            'type'=>'success',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
            'footer'=>false
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            'email',
            //'auth_key',
            //'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'status',
            [
                'attribute' => 'status',
                'label' => 'Status',
                'format'=>'html',
                'value' => function ($model) {
                    return $model->getLabeledStatus($model->status);
                },
                'vAlign' => GridView::ALIGN_MIDDLE,
            ],
//            [
//                'attribute' => 'roles',
//                'label' => 'Status',
//                'format'=>'html',
//                'value' => function ($model) {
//                    return $model->getRoles();
//                },
//                'vAlign' => GridView::ALIGN_MIDDLE,
//            ],
            //'roles',
            'created_at:datetime',
//
//            [
//                'attribute'=>'status',
//                'class'=>'\dixonstarter\togglecolumn\ToggleColumn',
//                'options'=>['style'=>'width:50px;'],
//                'linkTemplateOn'=>'<a class="toggle-column" data-pjax="0" href="{url}">{label}</a>',
//                'linkTemplateOff'=>'<a class="toggle-column" data-pjax="0" href="{url}">{label}</a>'
//            ],
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'responsiveWrap' => false,
    ]);  ?>
-->
</div>

<div class="row">

    <?php foreach ($dataProvider->models as $model) { ?>

    <div class="col-md-3">

        <?php
        if($model->status == Managers::STATUS_WAITING){
            echo "<div class='box box-warning'>";
        }elseif ($model->status == Managers::STATUS_ACTIVE){
            echo "<div class='box box-success'>";
        }else{
            echo "<div class='box box-danger'>";
        }?>
        <div class="box-header with-border">
            <h3 class="box-title" style="font-family: 'Maven Pro', sans-serif; text-transform: capitalize;"><span class="fa fa-user-o"></span> <?php echo $model->username; ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                <?= Html::a("<span class='fa fa-pencil'></span>",
                    ['/managers/update', 'id' => $model->id],
                    ['class'=>'btn btn-box-tool',
                        //'data-toggle' => 'tooltip', 'data-placement' => 'top','title' => 'Modify',
                        'data' => [
                            'confirm' => 'Are you sure you want to modify this manager?',
                            'method' => 'post',
                        ],
                    ]) ?>

                <?php
                //                if($model->status == Yii2User::STATUS_ACTIVE){
                //
                //                }else{
                echo  Html::a("<span class='fa fa-power-off'></span>",
                    ['/managers/deactivate','id' => $model->id],
                    ['class'=>'btn btn-box-tool',
                        //'data-toggle' => 'tooltip', 'data-placement' => 'top','title' => 'Deactivate',
                        'data' => [
                            'confirm' => 'Are you sure you want to deactivate this manager?',
                            'method' => 'post',
                        ],
                    ]);
                //}
                ?>

            </div>
        </div><!-- /.box-header -->
        <div class="box-body">
            <!--            <img src="https://www.codeproject.com/KB/GDI-plus/ImageProcessing2/img.jpg" class="img-responsive img-circle" width="200" style="padding:10px; margin: 0 auto"/>-->
            <!--            <img src="--><?//= Yii::getAlias('@web').'/img/icon.png';?><!--"-->
            <img src="<?= Yii::getAlias('@web').'/img/Manager-512.png';?>"  class="img-responsive img-circle" width="200" style="padding:10px; margin: 0 auto"/>
            <div style="padding-left: 10px; padding-right: 10px; margin-top:-15px; padding-top:-40px;">
                <hr/>
                <p><span class="fa fa-envelope-o"></span> Email : <?php echo $model->email; ?></p>
                <p><span class="fa fa-calendar-o"></span> Created : <?php echo Yii::$app->formatter->asDateTime($model->created_at,'medium') ?></p>
                <a style="color: black"><span class="fa fa-wifi"></span> Status : <?php echo $model->getStatus($model->status); ?></a>
                <p style="margin-top: 5px"> </p>
            </div>

        </div><!-- /.box-body -->

        <div class="box-footer" align="right">

            <?= Html::a("<span class='fa fa-file-text-o'></span>&nbsp;Details",
                ['/managers/view', 'id' => $model->id, 'rending' => 'ajax'],
                ['class'=>'btn btn-default btn-flat view_details',]) ?>

            <?php if($model->status == Managers::STATUS_WAITING){ ?>
                <?= Html::a("<span class='fa fa-check'></span>&nbsp;Activate",
                    ['/managers/activate', 'id' => $model->id],
                    ['class'=>'btn btn-default btn-flat',
                        'data' => [
                            'confirm' => 'Are you sure you want to Activate this manager?',
                            'method' => 'post',
                        ],
                    ]) ?>
            <?php }elseif ($model->status == Managers::STATUS_ACTIVE){?>
                <?= Html::a("<span class='fa fa-power-off'></span>&nbsp;DeActivate",
                    ['/managers/deactivate','id' => $model->id],
                    ['class'=>'btn btn-default btn-flat',
                        'data' => [
                            'confirm' => 'Are you sure you want to DeActivate this manager?',
                            'method' => 'post',
                        ],
                    ]) ?>
            <?php }elseif ($model->status == Managers::STATUS_DELETED){?>
                <?= Html::a("<span class='fa fa-check'></span>&nbsp;ReActivate",
                    ['/managers/activate', 'id' => $model->id],
                    ['class'=>'btn btn-default btn-flat',
                        'data' => [
                            'confirm' => 'Are you sure you want to Activate this manager?',
                            'method' => 'post',
                        ],
                    ]) ?>
            <?php }?>
        </div><!-- /.box-footer-->
    </div><!--/.direct-chat -->

</div>

<?php } ?>

</div>

<div class="clearfix"></div>
