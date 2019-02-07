<?php

namespace app\controllers;

use app\models\Branch;
use app\models\MSGBroadcaster;
use app\models\Users;
use app\models\VBranchRequests;
use Yii;
use app\models\BranchRequests;
use app\models\BranchRequestsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class BranchRequestsController extends Controller
{
    public $enableCsrfValidation = false;

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

    public function actionIndex()
    {
        $searchModel = new BranchRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->query->where('paid_amount < blood_amount');
//        ->where('paid_amount < blood_amount')
//        ->orWhere('blood_amount = 0')

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id, $rending = null)
    {
        if ($rending === "ajax") {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        $model = new BranchRequests();

        if ($model->load(Yii::$app->request->post())) {
            $users = new Users();
            $broadcaster = new MSGBroadcaster();
            $model->created = date('Y-m-d H:i:s');
            $model->req_key = $model->getRandomKey(15);
            $model->paid_amount = 0;
            $status = $model->validateKey($model->req_key);
            if ($status == 1) { // Exists
                $model->req_key = $model->getRandomKey(16);
            }
            $model->status = BranchRequests::REQUEST_OPEN;
            $model->save();

            $global_title = $model->branch->branch_name;
            $actual = $model->blood_amount - $model->paid_amount;
            $global_msg = 'Request '.$actual.' units of '.$model->bloodGroup->blood_name.' bloodgroup';
            $tokens = $users->getAllActivatedTokens();
            $broadcaster->sendMultipleBroadcast($global_title,$global_msg,$tokens);

            return $this->redirect(['view', 'id' => $model->req_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->virtual_remain_amount - $model->virtual_paid_amount == 0) {
                $model->status = BranchRequests::REQUEST_CLOSED; // CLOSE DONATION
            }
            $model->paid_amount += $model->virtual_paid_amount;
            $model->save();
            return $this->redirect(['view', 'id' => $model->req_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionOpening()
    {
        $searchModel = new BranchRequests();
        $query = $searchModel::find();
        $query->select('req_id, branch_requests.branch_id,branch.branch_id as bid, blood_group,paid_amount,status,created,sum(blood_amount) as blood_amount,branch.branch_name');

        $query->joinWith('branch');
        $query->where('branch_requests.status = 1');
        $query->groupBy("branch_id,created");
        //$query->sum('blood_amount');
        $query->all();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        // RenderAjax for render maps in tabs
        $data = $this->renderAjax('opening', [
            'searchModel' => $dataProvider,
            'dataProvider' => $dataProvider,
        ]);
        //return $data;
        return Json::encode($data);
    }

    public function actionClosed()
    {
        $searchModel = new BranchRequests();
        $query = $searchModel::find();
        $query->select('req_id, branch_requests.branch_id,branch.branch_id as bid, blood_group,paid_amount,status,created,sum(blood_amount) as blood_amount,branch.branch_name');

        $query->joinWith('branch');
        $query->where('branch_requests.status = 0');
        $query->groupBy("branch_id,created");
        //$query->sum('blood_amount');
        $query->all();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        // RenderAjax for render maps in tabs
        $data = $this->renderPartial('closed', [
            'searchModel' => $dataProvider,
            'dataProvider' => $dataProvider,
        ]);
        //return $data;
//        return Json::encode($data);

        $html = $this->renderAjax('sample');
        return Json::encode($html);
    }

    protected function findModel($id)
    {
        if (($model = BranchRequests::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_all_active()
    { // Paid Amount must not be null
        Yii::$app->response->format = Response::FORMAT_JSON;
        $requests = VBranchRequests::find()// SELECT FROM VIEW
            ->where('paid_amount < blood_amount')
            ->andWhere('status = :status', [':status' => 1])
            ->orWhere('blood_amount = 0')
            ->all();
        if (count($requests) > 0) {
            return array('status' => true, 'data' => $requests);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }
}
