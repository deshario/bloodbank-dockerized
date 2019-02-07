<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\DonationDayReservation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="donation-day-reservation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList($model->getUsersList(), ['prompt' => 'Select User']) ?>

    <?= $form->field($model, 'branch_id')->dropDownList($model->getBranchList(), ['prompt' => 'Select Branch']) ?>

    <?= $form->field($model, 'reserved_date')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Select date for reservation'],
        'pluginOptions' => [
            'autoclose'=>true,
            //'todayBtn' => true,
            'startDate' => date('Y-m-d H:i:s'),
            'format' => 'yyyy-mm-dd hh:ii:ss',
            'todayHighlight' => true
        ]
    ]);
    ?>

    <?= $form->field($model, 'user_notes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
