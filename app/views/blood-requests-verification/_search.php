<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequestsVerificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="donation-verified-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'donate_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'donated_by') ?>

    <?= $form->field($model, 'donated_to') ?>

    <?= $form->field($model, 'manager_id') ?>

    <?php // echo $form->field($model, 'verified') ?>

    <?php // echo $form->field($model, 'donated_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
