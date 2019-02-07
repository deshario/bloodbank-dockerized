<?php

namespace app\controllers;

use app\models\VRequestedBloodgroupAnalysis;
use app\models\VRequestStatusAnalysis;
use paragraph1\phpFCM\Client;
use Yii;
use yii\web\Response;

class AnalysisController extends \yii\web\Controller
{
    public function actionBlood_group()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VRequestedBloodgroupAnalysis::find()
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionRequest_status()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VRequestStatusAnalysis::find()
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }
}