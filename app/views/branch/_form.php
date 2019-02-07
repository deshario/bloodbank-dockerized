<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Branch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?php
    $startjs = <<<EOT
            function(component) {
                 $(document).ready(function(){
                    document.getElementById('branch-branch_lat_long-searchbox').value = '';
                }); 
            }
EOT;

    $changedjs = <<<EOT
        function (currentLocation, radius, isMarkerDropped) {
                var mapContext = $(this).locationpicker('map');
                mapContext.map.setZoom(13);
                var address = document.getElementById("branch-branch_lat_long-searchbox").value;
                $('#branch-branch_address').val(address);
                
        }
EOT;
    echo $form->field($model, 'branch_lat_long')->widget('\pigolab\locationpicker\CoordinatesPicker',[
        'key' => \Yii::$app->params['map_api_key'],
        'valueTemplate' => '{latitude},{longitude}',
        'options' => [
            'style' => 'width: 100%; height: 400px',
        ] ,
        'enableSearchBox' => true,
        'searchBoxOptions' => [
            'style' => 'width: 260px;',
        ],
        'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
        'mapOptions' => [
            'mapTypeControl' => true,
            'mapTypeControlOptions' => [
                'style'    => new JsExpression('google.maps.MapTypeControlStyle.DROPDOWN_MENU'),
                'position' => new JsExpression('google.maps.ControlPosition.LEFT_BOTTOM'),
            ],
            'streetViewControl' => true, // Enable Street View Control
        ],
        'clientOptions' => [ // jquery-location-picker options
            'oninitialized' => new JsExpression($startjs),
            'onchanged' => new JsExpression($changedjs),
            'radius'    => 300,
            'addressFormat' => 'street_number',
        ]
    ]);
    ?>

    <?= $form->field($model, 'branch_address')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
