<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BranchRequests */

$this->title = 'New Branch Request';
$this->params['breadcrumbs'][] = ['label' => 'Branch Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-requests-create">

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
