<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequestsVerification */

$this->title = 'Update Branch Requests Verification: ' . $model->donate_id;
$this->params['breadcrumbs'][] = ['label' => 'Branch Requests Verifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->donate_id, 'url' => ['view', 'id' => $model->donate_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="branch-requests-verification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
