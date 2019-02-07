<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"> <!-- Mini Here --> </span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Control Sidebar Toggle Button -->
                <!-- <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li> -->
                 <li><a href="<?= Url::toRoute(['/site/api']) ?>" target="_blank"><i class="fa fa-code-fork fa-lg"></i>&nbsp; REST API</a></li>
                 <li id="data_fail" style="display: none"><?= Html::a('<i class="fa fa-home"></i> HOME', ['login']);?></li>
            </ul>
        </div>

    </nav>
</header>
