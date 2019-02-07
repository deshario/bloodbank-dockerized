<?php

namespace app\controllers;

use app\models\BranchRequests;
use app\models\MSGBroadcaster;
use app\models\VBranchRequests;
use app\models\VBranchReqVerification;
use kartik\growl\Growl;
use Yii;
use app\models\BranchRequestsVerification;
use app\models\BranchRequestsVerificationSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BranchRequestsVerificationController implements the CRUD actions for BranchRequestsVerification model.
 */
class BranchRequestsVerificationController extends Controller
{

    public $enableCsrfValidation = false;  // HTTP REQUEST METHOD SECURITY

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
     * Lists all BranchRequestsVerification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchRequestsVerificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('verified = ' . BranchRequestsVerificationSearch::DONATION_PENDING);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPending()
    {
        $searchModel = new BranchRequestsVerificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('verified = ' . BranchRequestsVerificationSearch::DONATION_PENDING);
        $html = $this->renderPartial('donation_completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'showActionColumn' => 'show',
            'showViewColumn' => 'hide',
        ]);
        return Json::encode($html);
    }

    public function actionCompleted()
    {
        $searchModel = new BranchRequestsVerificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('verified = ' . BranchRequestsVerificationSearch::DONATION_VERIFIED);
        $html = $this->renderPartial('donation_completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'showActionColumn' => 'hide',
            'showViewColumn' => 'show',
        ]);
        return Json::encode($html);
    }

    /**
     * Displays a single BranchRequestsVerification model.
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
     * Creates a new BranchRequestsVerification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BranchRequestsVerification();
        if ($model->load(Yii::$app->request->post())) {
            $branch_req = BranchRequests::findOne($model->branch_requests_id);
            $temp_required = $branch_req->blood_amount;
            $branch_default_paid = $branch_req->paid_amount;
            $temp_paid = $branch_default_paid += $model->paid_amount;
            if ($branch_req->status == BranchRequests::REQUEST_CLOSED) {
                Yii::$app->getSession()->setFlash('req_closed', [
                    'type' => Growl::TYPE_WARNING,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'REQUEST CLOSED',
                    'message' => 'Please contact administrator for more information',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            } else if ($temp_paid > $temp_required) { // Paid > required
                Yii::$app->getSession()->setFlash('invalid_data', [
                    'type' => Growl::TYPE_DANGER,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'Invalid Data!',
                    'message' => 'Paid Amount is more than required amount !',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            } else {
                if ($branch_req->blood_amount == $branch_req->paid_amount) {
                    $branch_req->status = BranchRequests::REQUEST_CLOSED; // Close Donation
                }
                if ($branch_req->validate() && $branch_req->save()) {
                    $currentDateTime = date('Y-m-d H:i:s');
                    $model->donated_date = $currentDateTime;
                    $model->verified = ($model->verified ?: 0);
                    if ($model->validate() && $model->save()) {
                        Yii::$app->getSession()->setFlash('invalid_data', [
                            'type' => Growl::TYPE_SUCCESS,
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'title' => '  Verification Created',
                            'message' => 'Wait for approval from administrator',
                            'positonY' => 'bottom',
                            'positonX' => 'right'
                        ]);
                        return $this->redirect(['view', 'id' => $model->donate_id]);
                    }
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionVerify($id)
    {
        if (Yii::$app->request->isPost) {
            $broadcaster = new MSGBroadcaster();
            $model = $this->findModel($id);
            $model->verified = BranchRequestsVerification::DONATION_VERIFIED;

            $branch_req = BranchRequests::findOne($model->branch_requests_id);
            $branch_req->paid_amount += $model->paid_amount;

            $branch_req->save();
            $model->save();
            // Broadcaster
            $title = 'Verification Success';
            $msg = 'Donation for '.$model->branchRequests->branch->branch_name.' had been verified';
            $broadcaster->sendSingleBroadcast($title,$msg,$model->donor->profile_token);
        }
        return $this->redirect(['index']);
    }

    public function actionDeny($id)
    {
        if (Yii::$app->request->post()) {
            $broadcaster = new MSGBroadcaster();
            $model = $this->findModel($id);
            $model->delete();
            // Broadcaster
            $title = 'Verification Denied';
            $msg = 'Donation for '.$model->branchRequests->branch->branch_name.' had been denied';
            $broadcaster->sendSingleBroadcast($title,$msg,$model->donor->profile_token);
        }
        return $this->redirect(['index']);
    }
    /**
     * Updates an existing BranchRequestsVerification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->donate_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BranchRequestsVerification model.
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
     * Finds the BranchRequestsVerification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BranchRequestsVerification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BranchRequestsVerification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionVirtual()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $req_id = $parents[0];
                //$out = self::getSubCatList($cat_id);
                $out = BranchRequestsVerification::getRemainAmount($req_id);
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

//    public function actionHalo(){
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $countryList = BranchRequests::find()
//            ->select(['blood_amount as id', '(blood_amount-paid_amount) as name'])
//            ->asArray()->all();
//        return $countryList;
//    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionCreate_verifications()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $donation_verified = new BranchRequestsVerification();
            $donation_verified->scenario = BranchRequestsVerification::SCENARIO_CREATE;
            $donation_verified->attributes = \yii::$app->request->post();

            if ($donation_verified->validate()) {
                $branch_req = BranchRequests::findOne($donation_verified->branch_requests_id);
                $temp_required = $branch_req->blood_amount;
                $branch_default_paid = $branch_req->paid_amount;
                $temp_paid = $branch_default_paid += $donation_verified->paid_amount;
                if ($branch_req->status == BranchRequests::REQUEST_CLOSED) {
                    return array('status' => false, 'data' => 'REQUEST CLOSED');
                } else if ($temp_paid > $temp_required) { // Paid > required
                    return array('status' => false, 'data' => 'Paid Amount is more than required amount !');
                } else {
                    //$branch_req->paid_amount = $temp_paid;
                    if ($branch_req->blood_amount == $branch_req->paid_amount) {
                        $branch_req->status = BranchRequests::REQUEST_CLOSED; // Close Donation
                    }
                    if ($branch_req->validate() && $branch_req->save()) {
                        $currentDateTime = date('Y-m-d H:i:s');
                        $donation_verified->donated_date = $currentDateTime;
                        $donation_verified->verified = ($donation_verified->verified ?: 0);
                        if ($donation_verified->save()) {
                            return array('status' => true, 'data' => 'Branch verification successfully created');
                        } else {
                            return array('status' => false, 'data' => $donation_verified->getErrors());
                        }
                    }
                }
            } else {
                return array('status' => false, 'data' => $donation_verified->getErrors());
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

    public function actionGet_pending_donations($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = VBranchReqVerification::find()
            ->where(['donor_id' => $user_id])
            ->andWhere('verified = :verified', [':verified' => 0])
            ->orderBy([
                'donated_date' => SORT_DESC,
            ])
            ->all();
        if (count($model) > 0) { //return $model;
            return array('status' => true, 'data' => $model);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }
}
