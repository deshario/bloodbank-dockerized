<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = 'Sign In';

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
        <a href="#"><b>Cloud</b> BloodBank</a>
    </div>
    <!-- /.login-logo -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Login</h3>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <div class="panel-body">

           <!--  <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username'),'maxlength' => 8]) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            -->
            <?= $form->field($model, 'username')->textInput(
                    [   'autofocus' => true,
                        'placeholder' => $model->getAttributeLabel('username'),
                        'maxlength' => 8
                    ]) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

          <!--   <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <a href="#" class="btn btn-default btn-xs">Forgot password</a>
                </div>
            </div> -->

            <?= Html::submitButton('Sign in', ['class' => 'btn btn-default btn-block', 'name' => 'login-button']) ?>

        </div>
        <div class="panel-footer clearfix">
            <?= Html::a('<i class="fa fa-key"></i> Forgot Password', ['forgot'], ['class' => 'pull-left']);?>
            <?= Html::a('<i class="fa fa-user-plus"></i> Create an account', ['signup'], ['class' => 'pull-right']);?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <!-- /.login-box-body -->
</div><!-- /.login-box -->
