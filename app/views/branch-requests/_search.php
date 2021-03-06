<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'req_id') ?>

    <?= $form->field($model, 'branch_id') ?>

    <?= $form->field($model, 'blood_group') ?>

    <?= $form->field($model, 'blood_amount') ?>

    <?= $form->field($model, 'paid_amount') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
