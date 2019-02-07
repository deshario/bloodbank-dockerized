<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BloodRequestsVerification;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequestsVerification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="donation-verified-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->dropDownList($model->getRequestsList(),
        ['prompt' => 'Select Request Key', 'id' => 'req_id']) ?>

    <?= $form->field($model, 'donated_by')->dropDownList($model->getUsersList(), ['prompt' => 'Select Donor']) ?>

    <?= $form->field($model, 'virtual_remain_amount')->widget(DepDrop::classname(),[
        //'options' => ['id' => 'sub-reqid'],
        'pluginOptions' => [
            'depends' => ['req_id'],
            'placeholder' => false,
            'url' => Url::to(['/blood-requests-verification/get_remain_amount'])
        ]
    ]);
    ?>

    <?= $form->field($model, 'paid_amount')->textInput() ?>

    <?= $form->field($model, 'donated_to')->dropDownList($model->getUsersList(), ['prompt' => 'Select Receiver']) ?>

    <?= $form->field($model, 'manager_id')->dropDownList($model->getManagersList(), ['prompt' => 'Select Manager'])?>

    <?= $form->field($model, 'verified')->dropDownList([
        BloodRequestsVerification::DONATION_PENDING => $model->getDonationStatus(BloodRequestsVerification::DONATION_PENDING),
        BloodRequestsVerification::DONATION_VERIFIED => $model->getDonationStatus(BloodRequestsVerification::DONATION_VERIFIED),
    ],['prompt' => 'Select Status']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
