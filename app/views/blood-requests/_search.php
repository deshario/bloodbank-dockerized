<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blood-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'req_id') ?>

    <?= $form->field($model, 'requester_id') ?>

    <?= $form->field($model, 'donater_id') ?>

    <?= $form->field($model, 'blood_group') ?>

    <?= $form->field($model, 'blood_amount') ?>

    <?php // echo $form->field($model, 'paid_amount') ?>

    <?php // echo $form->field($model, 'lat_long') ?>

    <?php // echo $form->field($model, 'full_address') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'postal_code') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'req_key') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
