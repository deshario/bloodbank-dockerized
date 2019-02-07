<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Branch;

/* @var $this yii\web\View */
/* @var $model app\models\Branch */

$this->title = $model->branch_name;
$this->params['breadcrumbs'][] = ['label' => 'Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
    .Mshadow{
        -webkit-box-shadow: 3px 3px 5px 6px #ccc;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
        -moz-box-shadow:    3px 3px 5px 6px #ccc;  /* Firefox 3.5 - 3.6 */
        box-shadow:         3px 3px 5px 6px #ccc;
    }
");

?>
<div class="branch-view">

    <!-- <p>
        <?= Html::a('Update', ['update', 'id' => $model->branch_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->branch_id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
    </p>
-->

    <!--
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="20%">{label}</th><td> {value}</td></tr>',
        'attributes' => [
            //'branch_id',
            'branch_name',
            //'branch_lat_long',
            'branch_address',
            'branch_created',
            [
                'label' => 'Connected Accounts',
                'format' => 'html',
                'value' => '<code>' . $model->getManagers()->count() . '</code>',
            ],
            ['label' => 'Branch Code', 'format' => 'html', 'value' => '<code>' . $model->branch_code . '</code>'],
        ],
    ]) ?>
  -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Branch Details</h3>
        </div>
        <div class="panel-body" style="margin-bottom: -15px">

            <div style="margin-top: -5px;" class="Mshadow">
                <?php
                list($latitude, $longitude) = explode(',', $model->branch_lat_long);
                $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);
                $map = new Map([
                    'center' => $coord,
                    'zoom' => 16,
                    'width' => '100%',
                    'height' => '300',
                    //'mapTypeId' => \dosamigos\google\maps\MapTypeId::SATELLITE,
                ]);
                $marker = new Marker([
                    'position' => $coord,
                    'title' => $model->branch_name,
                ]);
                // Provide a shared InfoWindow to the marker
                $marker->attachInfoWindow(
                    new InfoWindow([
                        'content' => "<strong>" . $model->branch_name . "</strong><br><br><a href='#'>View on Google Maps</a>"
                    ])
                );
                // Add marker to the map
                $map->addOverlay($marker);
                echo $map->display();
                ?>
            </div>

            <div class="row" style="margin-top: 15px;">

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box Mshadow">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-hospital-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><strong>Branch Name</strong></span>
                            <span class="info-box-number"
                                  style="font-size: 16px; font-weight: normal"><?= $model->branch_name ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box Mshadow">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-key"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><strong>Branch Code</strong></span>
                            <span class="info-box-number"><code><?= $model->branch_code ?></code></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box Mshadow">
                        <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><strong>Staff Registrations</strong></span>
                            <span class="info-box-number" style="font-size: 20px">
                                <?php
                                $count = $model->getManagers()->count();
                                echo "<code>" . $count . "</code>";
                                ?>
                            </span>
                            <?php
                            if ($count > 0) {
                                echo "<a style='font-size: 14px;' class='btn btn-flat btn-block btn-default btn-xs' 
                                    data-toggle='modal' data-target='#myModal'><span class='fa fa-search-plus'></span> View</a>";
                            } ?>
                        </div>
                    </div>
                </div>

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box Mshadow">
                        <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><strong>Branch Created</strong></span>
                            <span class="info-box-number"><?= $model->getCustomDate($model->branch_created, null, false) ?></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="clearfix"></div>

</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= $model->branch_name ?>'s Staff</h4>
            </div>
            <div class="box-body no-padding">
                <ul class="users-list clearfix">

                    <?php
                    $managers = $model->getManagers()->all();
                    foreach ($model->getManagers()->all() as $item) {
                        $timeStamp = $item->created_at;
                        $dateTime = Branch::getDatetime($timeStamp);
                        $MFormatDatetime = Branch::getCustomDate($dateTime, null, false);
                        ?>

                        <li>
                            <img src="<?= Yii::getAlias('@web') ?>/img/Manager-512.png" class="img-responsive"
                                 alt="Bloodbank"/>
                            <a class="users-list-name" href="#"><?php echo $item->username; ?></a>
                            <span class="users-list-date"><?php echo $MFormatDatetime; ?></span>
                        </li>

                    <?php } ?>

                </ul>
            </div>
        </div>
    </div>
</div>
