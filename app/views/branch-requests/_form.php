<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord){ ?>

    <?= $form->field($model, 'branch_id')->dropDownList($model->getBranchList(), ['prompt' => 'Select Branch'])?>

    <?= $form->field($model, 'blood_group')->dropDownList($model->getBloodgroups(), ['prompt' => 'Select Bloodgroup'])?>

    <?= $form->field($model, 'blood_amount')->textInput() ?>

    <?php }else{

        $ask = $model->blood_amount;
        $paid = $model->paid_amount;
        $remain = $ask-$paid;
        $model->virtual_remain_amount = $remain;

        ?>

        <?= $form->field($model, 'branch_id')->dropDownList($model->getBranchList(), ['prompt' => 'Select Branch', 'readOnly' => 'true'])?>

        <?= $form->field($model, 'blood_group')->dropDownList($model->getBloodgroups(), ['prompt' => 'Select Bloodgroup', 'readOnly' => 'true'])?>

        <!-- <?= $form->field($model, 'blood_amount')->textInput(['readOnly' => 'true']) ?> -->

        <?= $form->field($model, 'virtual_remain_amount')->textInput() ?>

        <?= $form->field($model, 'virtual_paid_amount')->textInput() ?>

       <!--  <?= $form->field($model, 'paid_amount')->textInput() ?> -->

        <!-- <?= $form->field($model, 'created')->textInput() ?> -->

        <?= $form->field($model, 'status')->dropDownList($model->getCustomStatus()) ?>

    <?php }?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'pull-right btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
