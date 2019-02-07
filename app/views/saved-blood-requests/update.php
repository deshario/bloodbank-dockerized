<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBloodRequests */

$this->title = 'Update Saved: ' . $model->savedBy->username;
$this->params['breadcrumbs'][] = ['label' => 'Saved Blood Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->savedBy->username, 'url' => ['view', 'id' => $model->saved_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="saved-blood-requests-update">

    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?= $this->title; ?></h3>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
