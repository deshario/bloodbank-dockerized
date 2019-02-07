<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BranchRequests */

$this->title = 'Update Requests';
$this->params['breadcrumbs'][] = ['label' => 'Branch Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->branch->branch_name, 'url' => ['view', 'id' => $model->req_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="branch-requests-update">

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


</div>
