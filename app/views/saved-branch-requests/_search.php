<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBranchRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="saved-branch-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'saved_id') ?>

    <?= $form->field($model, 'requests_id') ?>

    <?= $form->field($model, 'saved_by') ?>

    <?= $form->field($model, 'saved_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
