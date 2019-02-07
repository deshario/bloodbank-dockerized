<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SavedBranchRequests */

$this->title = 'Update Saved Branch Requests: ' . $model->saved_id;
$this->params['breadcrumbs'][] = ['label' => 'Saved Branch Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->saved_id, 'url' => ['view', 'id' => $model->saved_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="saved-branch-requests-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
