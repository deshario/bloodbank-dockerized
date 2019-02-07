<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\BloodRequestsVerificationSearch;
use app\models\Managers;
use app\models\MSGBroadcaster;
use app\models\Users;
use app\models\VBloodRequests;
use Yii;
use app\models\BloodRequests;
use app\models\BloodRequestsSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;

/**
 * BloodRequestsController implements the CRUD actions for BloodRequests model.
 */
class BloodRequestsController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'only' => ['index', 'create', 'update', 'delete',],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => [
                            Managers::ROLE_MANAGER,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    //'create_request'=>['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all BloodRequests models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new BloodRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('status = ' . BloodRequests::REQUESTS_INCOMING);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIncoming()
    {
        $searchModel = new BloodRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('status = ' . BloodRequests::REQUESTS_INCOMING);
        $data = $this->renderPartial('custom_gridview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'MStatusSize' => '100px',
            'showActionColumn' => 'show',
            'showViewColumn' => 'hide',
        ]);
        return Json::encode($data);
    }

    public function actionApproved()
    {
        $searchModel = new BloodRequestsSearch();
        $query = $searchModel::find();
        $query->select('*,SUM(blood_amount) as MRequired, SUM(paid_amount) as MPaid');
        $query->where('status = ' . BloodRequests::REQUESTS_APPROVED);
        $query->groupBy("created,requester_id");
        $query->all();
        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        $data = $this->renderPartial('custom_gridview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'MStatusSize' => '150px',
            'showActionColumn' => 'hide',
            'showViewColumn' => 'show',
        ]);
        return Json::encode($data);

    }

    /**
     * Displays a single BloodRequests model.
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

    public function actionCustom($key)
    {
        return $this->render('view', [
            'model' => $this->findCustomModel($key),
        ]);
    }

    /**
     * Creates a new BloodRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new BloodRequests();
        if ($model->load(Yii::$app->request->post())) {
            $currentDateTime = date('Y-m-d H:i:s');
            //$model->location_name = 'DEFAULT'; // Request By PC
            $model->created = $currentDateTime;
            $model->status = BloodRequests::REQUESTS_INCOMING;
            if($model->reason == null){
                $model->reason = '';
            }
            $model->req_key = $model->getRandomKey(15);
            $status = $model->validateKey($model->req_key);
            if ($status == 1) { // Exists
                $model->req_key = $model->getRandomKey(16);
            }
            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BloodRequests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->req_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BloodRequests model.
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
     * Finds the BloodRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BloodRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = BloodRequests::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findCustomModel($key)
    {
        if ($model = BloodRequests::find()
            //->where(['req_key' => $key, 'req_key' => $key])
            ->where(['req_key' => $key])
            ->one()) {
            return $model;
        };
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAccept($id)
    {
        if (Yii::$app->request->isPost) {
            $users = new Users();
            $broadcaster = new MSGBroadcaster();
            $model = $this->findModel($id);
            $model->status = BloodRequests::REQUESTS_APPROVED;
            $model->save();

            //Broadcast
            $title = 'Request for '.$model->bloodGroup->blood_name.' had been Approved';
            $msg = $model->req_key.' is the alias of your request';
            $broadcaster->sendSingleBroadcast($title,$msg,$model->requester->profile_token);

            $global_title = $model->req_key.' request '.$model->bloodGroup->blood_name.' bloodgroup';
            $global_msg = 'Location : '.$model->location_name;
            $tokens = $users->getActivatedTokensExceptMine($model->requester->profile_token);
            if(count($tokens) > 0){
                $broadcaster->sendMultipleBroadcast($global_title,$global_msg,$tokens);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionDeny($id)
    {
        if (Yii::$app->request->post()) {
            $broadcaster = new MSGBroadcaster();
            $model = $this->findModel($id);
            $model->delete();

            //Broadcast
            $title = "Request for ".$model->bloodGroup->blood_name." had been disapproved";
            $msg = "Please contact nearby staff.";
            $broadcaster->sendSingleBroadcast($title,$msg,$model->requester->profile_token);
        }
        return $this->redirect(['index']);
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_all_active()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VBloodRequests::find()
            ->where('paid_amount < blood_amount')
            //->orWhere('blood_amount = 0')
            ->andWhere(['status' => BloodRequests::REQUESTS_APPROVED])
             ->orderBy([
	            'created'=>SORT_DESC,
        	])
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionGet_user_requests($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VBloodRequests::find()
            ->where('status between 0 and 2')
            ->andWhere('requester_id = :user_id', [':user_id' => $user_id])
             ->orderBy([
	            'created'=>SORT_DESC,
	            //'username' => SORT_DESC,
        	])
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionCreate_request()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $blood_req = new BloodRequests();
            $blood_req->scenario = BloodRequests::SCENARIO_CREATE;
            $blood_req->attributes = \yii::$app->request->post();
            $currentDateTime = date('Y-m-d H:i:s');
            $blood_req->created = $currentDateTime;
            $blood_req->status = BloodRequests::REQUESTS_INCOMING;
            if($blood_req->reason == null){
                $blood_req->reason = '';
            }
            $blood_req->req_key = $blood_req->getRandomKey(15);
            $status = $blood_req->validateKey($blood_req->req_key);
            if ($status == 1) { // Exists
                $blood_req->req_key = $blood_req->getRandomKey(16);
            }

            if ($blood_req->validate()) {
                $blood_req->save();
                return array('status' => true, 'data' => 'Record is successfully created');
            } else {
                return array('status' => false, 'data' => $blood_req->getErrors());
                //return array('status' => false, 'data' => 'Invalid data found');
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

}
