<?php

namespace app\controllers;

use app\models\VSavedBranchreq;
use kartik\growl\Growl;
use Yii;
use app\models\SavedBranchRequests;
use app\models\SavedBranchRequestsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SavedBranchRequestsController implements the CRUD actions for SavedBranchRequests model.
 */
class SavedBranchRequestsController extends Controller
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
     * Lists all SavedBranchRequests models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SavedBranchRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SavedBranchRequests model.
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
     * Creates a new SavedBranchRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SavedBranchRequests();

        if ($model->load(Yii::$app->request->post())){
            $currentDateTime = date('Y-m-d H:i:s');
            $model->saved_date = $currentDateTime;
            if($model->checkSaved($model) == true){ // OK
                $model->save();
                Yii::$app->getSession()->setFlash('saved_branch_req_success', [
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
                Yii::$app->getSession()->setFlash('already_saved_branch_req', [
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
     * Updates an existing SavedBranchRequests model.
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
     * Deletes an existing SavedBranchRequests model.
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
     * Finds the SavedBranchRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SavedBranchRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SavedBranchRequests::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_user_saved_req($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VSavedBranchreq::find()
            //->where(['user_id' => $user_id])
            ->where('user_id = :user_id', [':user_id' => $user_id])
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
            $model = new SavedBranchRequests();
            $model->scenario = SavedBranchRequests::SCENARIO_CREATE;
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
