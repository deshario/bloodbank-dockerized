<?php

namespace app\controllers;

use app\models\BloodRequests;
use app\models\MSGBroadcaster;
use app\models\VBloodReqVerification;
use kartik\growl\Growl;
use Yii;
use app\models\BloodRequestsVerification;
use app\models\BloodRequestsVerificationSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BloodRequestsVerificationController implements the CRUD actions for BloodRequestsVerification model.
 */
class BloodRequestsVerificationController extends Controller
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
     * Lists all BloodRequestsVerification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BloodRequestsVerificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('verified = ' . BloodRequestsVerification::DONATION_PENDING);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPending()
    {
        $searchModel = new BloodRequestsVerificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('verified = ' . BloodRequestsVerification::DONATION_PENDING);
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
        $searchModel = new BloodRequestsVerificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('verified = ' . BloodRequestsVerification::DONATION_VERIFIED);
        $html = $this->renderPartial('donation_completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'showActionColumn' => 'hide',
            'showViewColumn' => 'show',
        ]);
        return Json::encode($html);
    }

    public function actionGet_remain_amount()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $req_id = $parents[0];
                $out = BloodRequestsVerification::getRemainAmount($req_id);
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Displays a single BloodRequestsVerification model.
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
     * Creates a new BloodRequestsVerification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BloodRequestsVerification();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {

            $blood_req = BloodRequests::findOne($model->request_id);

            $temp_required = $blood_req->blood_amount;
            $blood_default_paid = $blood_req->paid_amount;
            $temp_paid = $blood_default_paid += $model->paid_amount;

            if ($blood_req->status == BloodRequests::REQUESTS_CLOSED) {
                Yii::$app->getSession()->setFlash('bloodreq_closed', [
                    'type' => Growl::TYPE_WARNING,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'REQUEST CLOSED',
                    'message' => 'Please contact administrator for more information',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            } else if ($temp_paid > $temp_required) { // Paid > required
                Yii::$app->getSession()->setFlash('bloodreq_verification_invalid', [
                    'type' => Growl::TYPE_DANGER,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'Invalid Data!',
                    'message' => 'Paid Amount is more than required amount !',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            } else {
                //$blood_req->paid_amount = $temp_paid;
                if ($blood_req->blood_amount == $blood_req->paid_amount) {
                    $blood_req->status = BloodRequests::REQUESTS_CLOSED; // Close Donation
                }
                if ($blood_req->validate() && $blood_req->save()) {
                    $currentDateTime = date('Y-m-d H:i:s');
                    $model->donated_date = $currentDateTime;
                    $model->verified = ($model->verified ?: 0);
                    if ($model->validate() && $model->save()) {
                        Yii::$app->getSession()->setFlash('bloodreq_verification_created', [
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
            $model->verified = BloodRequestsVerification::DONATION_VERIFIED;
            $model->manager_id = Yii::$app->user->identity->id;

            $blood_req = BloodRequests::findOne($model->request_id);
            $blood_req->paid_amount += $model->paid_amount;

            $blood_req->save();
            $model->save();
            // Broadcaster
            $title = 'Verification Success';
            $msg = 'Donation for '.$model->request->bloodGroup->blood_name.' had been verified';
            $broadcaster->sendSingleBroadcast($title,$msg,$model->donatedBy->profile_token);
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
            $msg = 'Donation for '.$model->request->bloodGroup->blood_name.' had been denied';
            $broadcaster->sendSingleBroadcast($title,$msg,$model->donatedBy->profile_token);
        }
        return $this->redirect(['index']);
    }


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
     * Deletes an existing BloodRequestsVerification model.
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
     * Finds the BloodRequestsVerification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BloodRequestsVerification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BloodRequestsVerification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_all_completed()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = VBloodReqVerification::find()->all();
        if (count($model) > 0) {
            return array('status' => true, 'data' => $model);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionGet_user_donations($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = VBloodReqVerification::find()
            ->where(['donor_id' => $user_id])// ->one()
            ->andWhere('donation_status = :donation_status', [':donation_status' => 1])
            ->orderBy([
                'donated_date' => SORT_DESC,
                //WHERE blood_requests_verification.verified = 1
            ])
            ->all();
        if (count($model) > 0) { //return $model;
            return array('status' => true, 'data' => $model);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionCreate_verifications()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $donation_verified = new BloodRequestsVerification();
            $donation_verified->scenario = BloodRequestsVerification::SCENARIO_CREATE;
            $donation_verified->attributes = \yii::$app->request->post();

            if ($donation_verified->validate()) {
                $blood_req = BloodRequests::findOne($donation_verified->request_id);

                $temp_required = $blood_req->blood_amount;
                $blood_default_paid = $blood_req->paid_amount;
                $temp_paid = $blood_default_paid += $donation_verified->paid_amount;

                if ($blood_req->status == BloodRequests::REQUESTS_CLOSED) {
                    return array('status' => false, 'data' => 'REQUEST CLOSED');
                } else if ($temp_paid > $temp_required) { // Paid > required
                    return array('status' => false, 'data' => 'Paid Amount is more than required amount !');
                } else {
                    //$blood_req->paid_amount = $temp_paid;
                    if ($blood_req->blood_amount == $blood_req->paid_amount) {
                        $blood_req->status = BloodRequests::REQUESTS_CLOSED; // Close Donation
                    }
                    if ($blood_req->validate() && $blood_req->save()) {
                        $currentDateTime = date('Y-m-d H:i:s');
                        $donation_verified->donated_date = $currentDateTime;
                        $donation_verified->verified = ($donation_verified->verified ?: 0);
                        if ($donation_verified->save()) {
                            return array('status' => true, 'data' => 'Blood verification successfully created');
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
        $model = VBloodReqVerification::find()
            ->where(['donor_id' => $user_id])
            ->andWhere('donation_status = :donation_status', [':donation_status' => 0])
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
