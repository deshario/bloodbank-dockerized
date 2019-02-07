<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SavedBloodRequests */

$this->title = 'New Request';
$this->params['breadcrumbs'][] = ['label' => 'Saved Blood Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saved-blood-requests-create">

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
