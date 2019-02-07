<?php

namespace app\controllers;

use app\models\VSavedBloodreq;
use kartik\growl\Growl;
use Yii;
use app\models\SavedBloodRequests;
use app\models\SavedBloodRequestsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SavedBloodRequestsController implements the CRUD actions for SavedBloodRequests model.
 */
class SavedBloodRequestsController extends Controller
{
    public $enableCsrfValidation = false;

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
     * Lists all SavedBloodRequests models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SavedBloodRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SavedBloodRequests model.
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
     * Creates a new SavedBloodRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SavedBloodRequests();

        if ($model->load(Yii::$app->request->post())) {
            $currentDateTime = date('Y-m-d H:i:s');
            $model->saved_date = $currentDateTime;
            if($model->checkSaved($model) == true){ // OK
                $model->save();
                Yii::$app->getSession()->setFlash('saved_blood_req_success', [
                    'type' => Growl::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-save',
                    'title' => 'Success',
                    'message' => 'Request Saved for this user',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['view', 'id' => $model->saved_id]);
            }else{ // Already Exists
                Yii::$app->getSession()->setFlash('already_saved_blood_req', [
                    'type' => Growl::TYPE_DANGER,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'Fail',
                    'message' => 'This Request was already saved by this user',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SavedBloodRequests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->saved_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SavedBloodRequests model.
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
     * Finds the SavedBloodRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SavedBloodRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SavedBloodRequests::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_user_saved_req($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VSavedBloodreq::find()
            ->where('donor_id = :donor_id', [':donor_id' => $user_id])
            ->orderBy([
                'saved_date'=>SORT_DESC,
            ])
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionCreate_save()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $model = new SavedBloodRequests();
            $model->scenario = SavedBloodRequests::SCENARIO_CREATE;
            $model->attributes = \yii::$app->request->post();

            $currentDateTime = date('Y-m-d H:i:s');
            $model->saved_date = $currentDateTime;

            if ($model->validate()) {
                if($model->checkSaved($model) == true){ // OK
                    $model->save();
                    return array('status' => true, 'data' => 'Request saved');
                }else{
                    return array('status' => true, 'data' => 'Request already saved');
                }
            } else {
                return array('status' => false, 'data' => $model->getErrors());
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }
}
