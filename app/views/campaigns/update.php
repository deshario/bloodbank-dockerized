<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Campaigns */

$this->title = 'Update Campaigns: ' . $model->campaign_id;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->campaign_id, 'url' => ['view', 'id' => $model->campaign_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="campaigns-update">

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
