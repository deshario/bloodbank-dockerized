<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Managers;

/* @var $this yii\web\View */
/* @var $model app\Models\Yii2User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 8]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'status')->dropDownList([
        Managers::STATUS_WAITING => $model->getStatus(Managers::STATUS_WAITING),
        Managers::STATUS_ACTIVE => $model->getStatus(Managers::STATUS_ACTIVE),
        Managers::STATUS_DELETED => $model->getStatus(Managers::STATUS_DELETED),
    ]) ?>

    <?= $form->field($model, 'roles')->dropDownList([
        Managers::ROLE_MANAGER => $model->getRoles(Managers::ROLE_MANAGER),
        Managers::ROLE_ADMIN => $model->getRoles(Managers::ROLE_ADMIN),
    ]) ?>

    <?= $form->field($model, 'worked_at')->dropDownList($model->getBranchList(), ['prompt' => 'Select Branch'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
