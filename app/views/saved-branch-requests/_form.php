<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBranchRequests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="saved-branch-requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'requests_id')->dropDownList($model->getValidateBranches(), ['prompt' => 'Select Branch',]) ?>

    <?= $form->field($model, 'saved_by')->dropDownList($model->getUsersList(), ['prompt' => 'Select Donor']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
