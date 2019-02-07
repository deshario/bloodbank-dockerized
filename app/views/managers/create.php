<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\Models\Yii2User */

$this->title = 'Create Manager';
$this->params['breadcrumbs'][] = ['label' => 'Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Create Manager</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</div>
