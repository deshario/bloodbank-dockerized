<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\Models\Yii2User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username];
$this->params['breadcrumbs'][] = 'Update';
?>

<?php
Modal::begin([
    'header' => 'Patient Name',
    'id' => 'modal',
    'size' => 'modal-md',
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>

<div class="user-update">

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Modifying <?= $model->username;?></h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->


</div>
