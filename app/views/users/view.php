<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->u_id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title point_me"><code><i><?= $this->title; ?></i></code></h3>
        </div>
        <div class="panel-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'u_id',
                    'username',
                    'blood_group',
                    'phone',
                    'profile_salt',
                    'profile_password',
                    'created',
                    'profile_token:ntext',
                ],
            ]) ?>
        </div>
    </div>

</div>
