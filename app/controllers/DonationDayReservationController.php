<?php

namespace app\controllers;

use app\models\VDonationReservation;
use Yii;
use app\models\DonationDayReservation;
use app\models\DonationDayReservationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DonationDayReservationController implements the CRUD actions for DonationDayReservation model.
 */
class DonationDayReservationController extends Controller
{

    public $enableCsrfValidation = false; // HTTP REQUEST METHOD SECURITY

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DonationDayReservation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DonationDayReservationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DonationDayReservation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DonationDayReservation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DonationDayReservation();

        if ($model->load(Yii::$app->request->post())){
            $model->reservation_key = $model->getRandomKey(4);
            $status = $model->validateKey($model->reservation_key);
            if ($status == 1){
                $model->reservation_key = $model->getRandomKey(5);
            }
            if ($model->validate() && $model->save()){
                return $this->redirect(['view', 'id' => $model->reserved_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DonationDayReservation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->reserved_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DonationDayReservation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DonationDayReservation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DonationDayReservation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DonationDayReservation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionCreate_reservations()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $donation = new DonationDayReservation();
            $donation->scenario = DonationDayReservation::SCENARIO_CREATE;
            $donation->attributes = \yii::$app->request->post();
            $donation->reservation_key = $donation->getRandomKey(4);
            $status = $donation->validateKey($donation->reservation_key);
            if ($status == 1){
                $donation->reservation_key = $donation->getRandomKey(5);
            }
            if ($donation->validate()) {
                $donation->save();
                return array('status' => true, 'data' => 'Day Reservation successfully created');
            } else {
                return array('status' => false, 'data' => $donation->getErrors());
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

    public function actionGet_user_reservations($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VDonationReservation::find()
            ->where('user_id = :user_id', [':user_id' => $user_id])
            ->orderBy([
                'reserved_date'=>SORT_ASC,
            ])
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }
}
