<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DonationDayReservationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="donation-day-reservation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'reserved_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'branch_id') ?>

    <?= $form->field($model, 'user_notes') ?>

    <?= $form->field($model, 'reservation_key') ?>

    <?php // echo $form->field($model, 'reserved_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
