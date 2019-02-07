<?php

namespace app\controllers;

use app\models\CampaignsSubscribed;
use app\models\MSGBroadcaster;
use app\models\Users;
use app\models\VCampaigns;
use app\models\VCampaignsSubscribed;
use Helper\Scenario;
use kartik\growl\Growl;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Yii;
use app\models\Campaigns;
use app\models\CampaignsSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CampaignsController implements the CRUD actions for Campaigns model.
 */
class CampaignsController extends Controller
{
    public $enableCsrfValidation = false; // HTTP REQUEST METHOD SECURITY

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
        $searchModel = new CampaignsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Campaigns();

        if ($model->load(Yii::$app->request->post())) {
            $photo = UploadedFile::getInstance($model, 'campaign_img');
            $random_num = rand(0, 9999);
            $fileName = $random_num . '_' . time() . '_' . date('dmHi') . '.' . $photo->extension; // 9185_1530960936_07071755.jpg
            $photo->saveAs(Yii::getAlias('@webroot') . '/uploads/campaigns/' . $fileName);
            $model->campaign_img = $fileName;
            $model->campaign_created = date('Y-m-d H:i:s');
            $model->campaign_creator = Yii::$app->user->identity->getId();
            $model->campaign_status = Campaigns::CAMP_ACTIVE;
            $code = strtoupper(substr(Yii::$app->user->identity->username, 0, 3)) . date('dmHi');
            $model->campaign_key = $code . '.' . $model->getRandomKey(15); // ADM07071829.PNnzjiJKIX4v4zS
            $model->save();
            return $this->redirect(['view', 'id' => $model->campaign_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        Yii::$app->getSession()->setFlash('login_success', [
            'type' => Growl::TYPE_DANGER,
            'duration' => 5000,
            'icon' => 'fa fa-close',
            'title' => ' Invalid URL',
            'message' => 'Sorry, the page not found',
            'positonY' => 'bottom',
            'positonX' => 'right'
        ]);
        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->goHome();
        }
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->campaign_id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
    }

    public function actionBroadcast($campaign_key)
    {
        $virtual = new Campaigns();
        $model = $this->findCustomModel($campaign_key);
        if ($model->load(Yii::$app->request->post())) {
            $broadcaster = new MSGBroadcaster();
            $tokens = $virtual->getSubscribedTokens($model->campaign_id);
            if (count($tokens) > 0) {
                $broadcaster->sendMultipleBroadcast($model->broadcast_title,$model->broadcast_message,$tokens);
                Yii::$app->getSession()->setFlash('campaign_broadcast_success', [
                    'type' => Growl::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'title' => 'Broadcast Success',
                    'message' => 'Notifications delivered to subscribers',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            } else {
                Yii::$app->getSession()->setFlash('campaign_broadcast_warning', [
                    'type' => Growl::TYPE_WARNING,
                    'duration' => 5000,
                    'icon' => 'fa fa-bell-o',
                    'title' => 'Broadcast Alert',
                    'message' => 'No Subscribers for this campaign',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
            }
             return $this->redirect(['view', 'id' => $model->campaign_id]);
        }else{
            return $this->render('broadcast_form', [
                'model' => $model
            ]);
        }
    }

    public function actionSubscribers($campaign_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = VCampaignsSubscribed::find()
            //->select('profile_token')
            ->where(['campaign_id' => $campaign_id])
            ->all();
        if (count($model) > 0) {
            return $model;
        } else {
            return array();
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Campaigns::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findCustomModel($key)
    {
        if ($model = Campaigns::find()
            ->where(['campaign_key' => $key])
            ->one()) {
            return $model;
        };
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_all_active()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $camps = VCampaigns::find()->all();
        if (count($camps) > 0) {
            return array('status' => true, 'data' => $camps);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionGet_single($key)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Campaigns::find()
            ->where(['campaign_key' => $key])
            ->one();
        if (count($model) > 0) {
            return array('status' => true, 'data' => $model);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

    public function actionMy_subscriptions($user_id, $camp_id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = null;
        if ($camp_id == null) {
            $model = VCampaignsSubscribed::find()
                ->where(['u_id' => $user_id])
                ->groupBy('campaign_id')
                ->all();
        } else {
            $model = VCampaignsSubscribed::find()
                ->where(['u_id' => $user_id])
                ->andWhere('campaign_id = :camp_id', [':camp_id' => $camp_id])
                ->groupBy('campaign_id')
                ->all();
        }
        if (count($model) > 0) {
            return array('status' => true, 'data' => $model);
        } else {
            return array('status' => false, 'data' => 'No Subscriptions Found');
        }
    }

    public function actionSubscribe()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $subscription = new CampaignsSubscribed();
            $subscription->scenario = CampaignsSubscribed::SCENARIO_CREATE;
            $subscription->attributes = \yii::$app->request->post();
            $model = CampaignsSubscribed::find()
                ->where(['subscribed_campaign' => $subscription->subscribed_campaign])
                ->andWhere('subscribed_by = :subscribed_by', [':subscribed_by' => $subscription->subscribed_by])
                ->all();
            if (count($model) > 0) {
                return array('status' => true, 'data' => 'Subscription already created');
            } else {
                $currentDateTime = date('Y-m-d H:i:s');
                $subscription->subscribed_date = $currentDateTime;
                if ($subscription->validate()) {
                    $subscription->save();
                    return array('status' => true, 'data' => 'Subscription successfully created');
                } else {
                    return array('status' => false, 'data' => $subscription->getErrors());
                }
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

    public function actionUnsubscribe()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $subscription = new CampaignsSubscribed();
            $subscription->scenario = CampaignsSubscribed::SCENARIO_UNSUBSCRIBE;
            $subscription->attributes = \yii::$app->request->post();
            $model = CampaignsSubscribed::find()
                ->where(['subscribed_campaign' => $subscription->subscribed_campaign])
                ->andWhere('subscribed_by = :subscribed_by', [':subscribed_by' => $subscription->subscribed_by])
                ->one();
            if ($subscription->validate()) {
                if (count($model) > 0) {
                    if ($model->delete()) {
                        return array('status' => true, 'data' => 'Unsubscribe Success');
                    } else {
                        return array('status' => false, 'data' => 'Unsubscribe Fail');
                    }
                } else {
                    return array('status' => false, 'data' => 'No Subscriptions Found');
                }
            } else {
                return array('status' => false, 'data' => $subscription->getErrors());
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }


}
