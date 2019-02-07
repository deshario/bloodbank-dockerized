<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CampaignsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaigns-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'campaign_id') ?>

    <?= $form->field($model, 'campaign_name') ?>

    <?= $form->field($model, 'campaign_desc') ?>

    <?= $form->field($model, 'campaign_img') ?>

    <?= $form->field($model, 'campaign_created') ?>

    <?php // echo $form->field($model, 'campaign_coordinates') ?>

    <?php // echo $form->field($model, 'campaign_address') ?>

    <?php // echo $form->field($model, 'campaign_key') ?>

    <?php // echo $form->field($model, 'campaign_creator') ?>

    <?php // echo $form->field($model, 'campaign_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
