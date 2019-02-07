<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;
use dosamigos\google\maps\MapAsset;
use app\models\Campaigns;

MapAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Campaigns */

$this->title = $model->campaign_name;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
.modal-content{border-radius:5px;}
 #content_img{
    padding: 25px; 
    background-repeat: no-repeat; 
    background-size:100% 100%;
    max-height : 400px;
    //max-width : 100%;
} 
");

$this->registerJs("
    $(function() { 
    
     $('.view_map').click(function(event){
         event.preventDefault(); 
           var MTitle = '$model->campaign_name'; 
          //var title = this.getAttribute('data-title');  
          $('#campaignModal .custom_title').html(MTitle);
          $('#campaignModal').modal('show')
         return false;
     });
     
 });
");

?>
<?php
Modal::begin([
    'header' => '<span class="fa fa-bell-o fa-lg"></span>&nbsp; <a class="custom_title" style="color: white; font-size: 18px;">e</a>',
    'id' => 'campaignModal',
    'headerOptions' => ['class' => 'modal-header modal-header-primary'],
    'size' => 'modal-md',
]); ?>

<div class="row" style="margin-top: -15px; margin-bottom: -15px;">
    <?php
    list($latitude, $longitude) = explode(',', $model->campaign_coordinates);
    $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);
    $map = new Map([
        'center' => $coord,
        'zoom' => 16,
        'width' => '100%',
        'height' => '400',
    ]);
    $marker = new Marker([
        'position' => $coord,
        'title' => $model->campaign_name
    ]);

    $marker->attachInfoWindow(
        new InfoWindow([
            'content' => $model->campaign_name
        ])
    );
    // Add marker to the map
    $map->addOverlay($marker);
    echo $map->display();
    ?>
</div>

<?php Modal::end();
?>

<div class="campaigns-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><span class="fa fa-bell-o"></span>&nbsp; <?= $model->campaign_name; ?></h3>

        </div>
        <div class="box-body">

            <div style="margin: -9.8px">
               <!-- <div id="content_img" style="background-image: url('<?//= $image ?>')"></div> -->
            <img src="<?= Yii::$app->request->baseUrl.'/uploads/campaigns/'.$model->campaign_img;?>" class="center-block img-responsive" style="max-height: 300px" alt="">
            </div>

            <!--  <img src="https://images3.alphacoders.com/823/thumb-1920-82317.jpg" class="center img-responsive" style="max-height: 400px;"/> -->
            <div class="clearfix" style="margin-top: 15px"></div>
            <?= DetailView::widget([
                'model' => $model,
                'template' => '<tr><th width="15%">{label}</th><td> {value}</td></tr>',
                'attributes' => [
                    //'campaign_id',
                    'campaign_name',
                    'campaign_desc',
                    //'campaign_img',
                    //'campaign_coordinates',
                    //'campaign_address:ntext',
                    ['attribute' => 'campaign_address',
                        'format' => 'html',
                        'value' => $model->campaign_address." "
                            //.Html::a("<span class='fa fa-map-marker'></span>&nbsp; View Map", ['#']
                              //  ,['class' => 'btn btn-xs btn-default view_map',]),
                    ],
                    //'campaign_created',
                    ['attribute' => 'campaign_created', 'value' => $model->getCustomDate($model->campaign_created, Campaigns::DATE_MEDIUM, true)],
                    ['attribute' => 'campaign_key', 'format' => 'html', 'value' => "<code>" . $model->campaign_key . "</code>"],
                    ['attribute' => 'campaign_creator', 'label' => 'Created By', 'format' => 'html', 'value' => "<code>" . $model->campaignCreator->username . "</code>"],
                    //'campaign_status',
                    ['attribute' => 'campaign_status', 'format' => 'html', 'value' => "<code>" . $model->getCampaignStatus($model->campaign_status) . "</code>"],
                ],
            ]) ?>

        </div>
        <div class="box-footer">
            <?= Html::a("<span class='fa fa-map-marker'></span>&nbsp; Open Address on Map", ['#'],
                ['class' => 'btn btn-default btn-sm view_map',]) ?>
            <?php echo Html::a("<span class='fa fa-edit'></span>&nbsp; Broadcast Message",
                ['broadcast', 'campaign_key' => $model->campaign_key],
                ['class' => 'btn btn-default btn-sm',]); ?>
        </div>
    </div>

</div>