<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BranchRequestsVerification;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequestsVerification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-requests-verification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'branch_requests_id')->dropDownList($model->getBranchKeys(),
        ['prompt' => 'Select Request Key', 'id' => 'branch_key']) ?>

    <?= $form->field($model, 'donor_id')->dropDownList($model->getDonorsList(), ['prompt' => 'Select Donor']) ?>

    <?= $form->field($model, 'virtual_remain_amount')->widget(DepDrop::classname(), [ // Child1
        'options' => ['id' => 'subcat-id'],
        'pluginOptions' => [
            'depends' => ['branch_key'],
            'placeholder' => false,
            'url' => Url::to(['/branch-requests-verification/virtual'])
        ]
    ]);
    ?>

    <?= $form->field($model, 'paid_amount')->textInput() ?>

    <?= $form->field($model, 'verified')->dropDownList([
        BranchRequestsVerification::DONATION_PENDING => $model->getDonationStatus(BranchRequestsVerification::DONATION_PENDING),
        BranchRequestsVerification::DONATION_VERIFIED => $model->getDonationStatus(BranchRequestsVerification::DONATION_VERIFIED),
    ], ['prompt' => 'Select Status']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
