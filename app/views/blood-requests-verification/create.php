<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BloodRequestsVerification */

$this->title = 'Create Verification';
$this->params['breadcrumbs'][] = ['label' => 'Blood Requests Verification', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="donation-verified-create">

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
