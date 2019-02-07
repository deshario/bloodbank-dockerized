<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\BloodRequests */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
    $(document).ready(function(){
        var aa = document.getElementById('bloodrequests-lat_long-searchbox').value;
        
    }); 
", View::POS_END, 'my-options');
?>

<div class="blood-requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord){ ?>
    <!-- CREATE -->

        <?= $form->field($model, 'requester_id')->dropDownList($model->getUsersList()) ?>
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <?= $form->field($model, 'blood_group')->dropDownList($model->getBloodgroups(), ['prompt' => 'Select Bloodgroup'])?>
            </div>
            <div class="col-sm-6 col-md-6">
                <?= $form->field($model, 'blood_amount')->textInput(['maxlength' => true, 'placeholder' => 'Amount Of Units']) ?>
            </div>
        </div>

        <?php
        $startjs = <<<EOT
            function(component) {
                 $(document).ready(function(){
                    document.getElementById('bloodrequests-lat_long-searchbox').value = '';
                }); 
            }
EOT;


        $changedjs = <<<EOT
        function (currentLocation, radius, isMarkerDropped) {
                var mapContext = $(this).locationpicker('map');
               // mapContext.map.setZoom(13);
               
                var address = document.getElementById("bloodrequests-lat_long-searchbox").value; 
                var adr_components = $(this).locationpicker('map').location.addressComponents;  
                $('#bloodrequests-full_address').val(address);
                $('#bloodrequests-postal_code').val(adr_components.postalCode); 
                console.log(mapContext);
        }
EOT;

        echo $form->field($model, 'lat_long')->widget('\pigolab\locationpicker\CoordinatesPicker',[
            'key' => \Yii::$app->params['map_api_key'],
            'valueTemplate' => '{latitude},{longitude}',
            'options' => [
                'style' => 'width: 100%; height: 400px',  // map canvas width and height
            ] ,
            'enableSearchBox' => true , // Optional , default is true
            'searchBoxOptions' => [ // searchBox html attributes
                'style' => 'width: 260px;', // Optional , default width and height defined in css coordinates-picker.css
                //'class' => 'form-control',
            ],
            'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
            'mapOptions' => [
                'mapTypeControl' => true, // Enable Map Type Control
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



        <div class="row">
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'location_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'full_address')->textInput(['maxlength' => true, 'readOnly' => 'true']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true, 'readOnly' => 'true']) ?>
            </div>
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'created')->textInput(['maxlength' => 'true', 'readOnly' => 'true', 'value' => $model->getCurrentDatetime()]) ?>
            </div>
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'req_key')->textInput(['maxlength' => 'true', 'readOnly' => 'true', 'placeholder' => 'Automatically Generated']) ?>
            </div>
        </div>

    
    <?php }else{ ?>
        <!-- UPDATE -->
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <?= $form->field($model, 'requester_id')->dropDownList($model->getUsersList(),['readonly' => 'true']) ?>
            </div>
            <div class="col-sm-6 col-md-6">
                <?= $form->field($model, 'donater_id')->dropDownList($model->getUsersList()) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'blood_group')->textInput(['maxlength' => true, 'readonly' => true]) ?>
            </div>
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'blood_amount')->textInput(['maxlength' => true, 'placeholder' => 'Units', 'readonly' => true]) ?>
            </div>
            <div class="col-sm-4 col-md-4">
                <?= $form->field($model, 'paid_amount')->textInput() ?>
            </div>
        </div>

        <?= $form->field($model, 'lat_long')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>

        <?= $form->field($model, 'full_address')->textarea(['rows' => 6, 'readonly' => 'true']) ?>

        <?= $form->field($model, 'reason')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>

        <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>

        <?= $form->field($model, 'status')->textInput(['readonly' => 'true'])?>

    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Request' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
