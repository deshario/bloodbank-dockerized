<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequestsVerificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-requests-verification-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'donate_id') ?>

    <?= $form->field($model, 'branch_requests_id') ?>

    <?= $form->field($model, 'donor_id') ?>

    <?= $form->field($model, 'verified') ?>

    <?= $form->field($model, 'donated_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
