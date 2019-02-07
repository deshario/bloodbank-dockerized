<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Broadcast Message';
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->campaign_name, 'url' =>  ['view', 'id'=> $model->campaign_id],];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campaigns-broadcast">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $this->title; ?></h3>
        </div>
        <div class="panel-body">

            <?php $form = ActiveForm::begin(); // important
            ?>

            <?= $form->field($model, 'broadcast_title')->textInput() ?>

            <?= $form->field($model, 'broadcast_message')->textarea(['rows' => '6']) ?>

            <div class="form-group">
                <?= Html::submitButton('Send Broadcast', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
