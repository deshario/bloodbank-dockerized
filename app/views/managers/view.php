<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\Models\Yii2User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="managers-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Profile</h4>
        </div>
        <div class="panel-body">

            <?= DetailView::widget([
                'model' => $model,
                'template'=>'<tr><th width="20%">{label}</th><td> {value}</td></tr>',
                'attributes' => [
                    //'id',
                    'username',
                    //'auth_key',
                    //'password_hash',
                    //'password_reset_token',
                    'email:email',
                    //'status',
                    //'roles',
                    ['label' => 'Status', 'value' => $model->getStatus()],
                    ['label' => 'Roles', 'value' => $model->getRoles()],
                    //'created_at',
                    'created_at:datetime',
                    'updated_at:datetime',
                    //'worked_at',
                    ['label' => 'Worked At', 'value' => $model->workedAt->branch_name,],
                    ['label' => 'Secret Key', 'format' => 'html', 'value' => '<code><i>' . $model->manager_key . '</i></code>'],
                ],
            ]) ?>


        </div>
    </div>

</div>
