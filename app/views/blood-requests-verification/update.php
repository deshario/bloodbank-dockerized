<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequestsVerification */

$this->title = 'Update Donation Verified: ' . $model->donate_id;
$this->params['breadcrumbs'][] = ['label' => 'Donation Verifieds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->donate_id, 'url' => ['view', 'id' => $model->donate_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="donation-verified-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
