<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBloodRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="saved-blood-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'saved_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'donated_by') ?>

    <?= $form->field($model, 'donated_to') ?>

    <?= $form->field($model, 'saved_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
