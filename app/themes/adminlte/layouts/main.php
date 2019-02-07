<?php
use yii\helpers\Html;
use kartik\growl\Growl;

/* @var $this \yii\web\View */
/* @var $content string */

$this->registerCss("
.breadcrumb{
    background:#fff;
    border-color: #ddd;
    border: 1px solid transparent;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .05)
}
");

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
//    echo $this->render(
//        'main-login',
//        ['content' => $content]
//    );
}elseif (Yii::$app->controller->action->id === 'signup'){
//    echo $this->render(
//        'main-signup',
//        ['content' => $content]
//    );
}else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);
    rmrevin\yii\fontawesome\AssetBundle::register($this);

    //$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    $directoryAsset = Yii::getAlias('@web');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/cloud.png" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <style>
            h4 {display: inline;}
        </style>
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        <?php $this->head() ?>
    </head>

    <!-- <?php if (Yii::$app->user->isGuest) { ?>
    <body class="hold-transition skin-green sidebar-mini">
    <?php } else { ?>
    <body class="hold-transition skin-red sidebar-mini">
    <?php } ?> -->

    <body class="hold-transition skin-red sidebar-mini">

    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>


        <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
            <?php
            echo Growl::widget([
                'type' => (!empty($message['type'])) ? $message['type'] : 'info',
                'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
                'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
                'showSeparator' => true,
                'delay' => 1, //This delay is how long before the message shows
                'pluginOptions' => [
                    'showProgressbar' => true,
                    'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                    'placement' => [
                        'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                        'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                    ]
                ]
            ]);
            ?>
        <?php endforeach; ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
