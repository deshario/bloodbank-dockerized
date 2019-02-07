<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBloodRequests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="saved-blood-requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->dropDownList($model->getRequestsList(), ['prompt' => 'Select Request Key',]) ?>

    <?= $form->field($model, 'saved_by')->dropDownList($model->getUsersList(), ['prompt' => 'Select Donor']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
