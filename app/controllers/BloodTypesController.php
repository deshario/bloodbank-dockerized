<?php

namespace app\controllers;

use Yii;
use app\models\BloodTypes;
use app\models\BloodTypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BloodTypesController implements the CRUD actions for BloodTypes model.
 */
class BloodTypesController extends Controller
{
    public $enableCsrfValidation = false; // HTTP REQUEST METHOD SECURITY

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create_request'],
                'formats' => ['application/json' => Response::FORMAT_JSON]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_types()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $types = BloodTypes::find()
            ->all();
        if (count($types) > 0) {
            return array('status' => true, 'data' => $types);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionCreate_type()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $bloodType = new BloodTypes();
            $bloodType->scenario = BloodTypes::SCENARIO_CREATE;
            $bloodType->attributes = \yii::$app->request->post();
            if ($bloodType->validate()) {
                $bloodType->save();
                return array('status' => true, 'data' => 'Record is successfully created');
            } else {
                //return array('status' => false, 'data' => $bloodType->getErrors());
                return array('status' => false, 'data' => 'Something was error');
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

}
