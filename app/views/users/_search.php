<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'u_id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'blood_group') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'profile_salt') ?>

    <?php // echo $form->field($model, 'profile_password') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'profile_token') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
