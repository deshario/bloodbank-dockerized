<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = 'Sign Up';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#" style="color: #f8f8f8"><b>Cloud</b> BloodBank</a>
    </div>
    <!-- /.login-logo -->

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">New Account</h3>
        </div>

        <div class="panel-body">

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'Tpassword')->passwordInput() ?>

                <?= $form->field($model, 'confirm_password')->passwordInput() ?>

                <?= Html::submitButton('Signup', ['class' => 'btn btn-block btn-primary', 'name' => 'signup-button']) ?>

        </div>
        <div class="panel-footer clearfix text-center">
           <?= Html::a('<i class="fa fa-sign-in"></i> Already have an account', ['login'], ['class' => '']);?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <!-- /.login-box-body -->
</div><!-- /.login-box -->
