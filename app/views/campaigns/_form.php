<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Campaigns */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaigns-form">

    <?php
    $model->virtual_creator = Yii::$app->user->identity->username;
    $startjs = <<<EOT
            function(component) {
                 $(document).ready(function(){
                    document.getElementById('campaigns-campaign_coordinates-searchbox').value = '';
                    document.getElementById('campaigns-campaign_coordinates').value = '';
                }); 
            }
EOT;

    $changedjs = <<<EOT
        function (currentLocation, radius, isMarkerDropped) {
                var mapContext = $(this).locationpicker('map');
                mapContext.map.setZoom(13);
                var address = document.getElementById("campaigns-campaign_coordinates-searchbox").value;
                $('#campaigns-campaign_address').val(address);
                
                 //var mape1 = $('#campaigns-campaign_coordinates-map').locationpicker("map");
                 //console.log(mape1);                
        }
EOT;

    ?>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']]); // important
    ?>

    <?= $form->field($model, 'campaign_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'campaign_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'campaign_img')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'allowedFileExtensions' => ['jpg', 'gif', 'png'],
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ],
    ]);
    ?>

    <?= $form->field($model, 'campaign_coordinates')->widget('\pigolab\locationpicker\CoordinatesPicker', [
        'key' => \Yii::$app->params['map_api_key'],
        'valueTemplate' => '{latitude},{longitude}',
        'options' => [
            'style' => 'width: 100%; height: 400px',
        ],
        'enableSearchBox' => true,
        'searchBoxOptions' => [
            'style' => 'width: 260px;',
        ],
        'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
        'mapOptions' => [
            'mapTypeControl' => true,
            'mapTypeControlOptions' => [
                'style' => new JsExpression('google.maps.MapTypeControlStyle.DROPDOWN_MENU'),
                'position' => new JsExpression('google.maps.ControlPosition.LEFT_BOTTOM'),
            ],
            'streetViewControl' => true, // Enable Street View Control
        ],
        'clientOptions' => [ // jquery-location-picker options
            'oninitialized' => new JsExpression($startjs),
            'onchanged' => new JsExpression($changedjs),
            'radius' => 300,
            'addressFormat' => 'street_number',
        ]
    ]);
    ?>

    <?= $form->field($model, 'campaign_address')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'virtual_creator')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
