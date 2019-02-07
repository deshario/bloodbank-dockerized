<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BranchRequestsVerification */

$this->title = 'Create Branch Requests Verification';
$this->params['breadcrumbs'][] = ['label' => 'Branch Requests Verification', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-requests-verification-create">

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
