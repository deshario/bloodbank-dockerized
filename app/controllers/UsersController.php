<?php

namespace app\controllers;

use kartik\growl\Growl;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\User;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
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
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        if ($model->load(Yii::$app->request->post())) {
            $currentDateTime = date('Y-m-d H:i:s');
            $model->created = $currentDateTime;

            $model->virtual_password = 'TEMPORARY';
            $model->profile_token = 'empty';
            $hash = $model->gethashSSHA($model->profile_password);
            $encrypted_password = $hash["encrypted"]; // encrypted password
            $salt = $hash["salt"];
            $model->profile_salt = $salt;
            $model->profile_password = $encrypted_password;

            if ($model->validate() && $model->save()) {
                Yii::$app->getSession()->setFlash('user_create_success', [
                    'type' => Growl::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'title' => ' Registered Success',
                    'message' => 'New User Added',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['view', 'id' => $model->u_id]);
            } else {
                Yii::$app->getSession()->setFlash('user_create_fail', [
                    'type' => Growl::TYPE_DANGER,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'Registered Fail',
                    'message' => 'Something went wrong ! Please try again.',
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

    public function actionRemove()
    {
        $user = $this->findModel(1);
        $user->profile_token = null;
        $user->virtual_password = "virtual";
        if ($user->validate() && $user->save()) {
            return 'saved';
        } else {
            print_r($user->getErrors());
        }
    }

    public function actionOthersactivetokens($my_token)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Users::find()
            ->where(['not', ['profile_token' => null]])
            ->andWhere(['not', ['profile_token' => '']])
            ->andWhere('profile_token != :profile_token', [':profile_token' => $my_token])
            ->all();
        if (count($model) > 0) {
            return $model;
        } else {
            return array();
        }
    }

    public function actionAllactivetokens()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Users::find()
            ->where(['not', ['profile_token' => null]])
            ->andWhere(['not', ['profile_token' => '']])
            ->all();
        if (count($model) > 0) {
            return $model;
        } else {
            return array();
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->u_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $model = new Users();
            $model->scenario = Users::SCENARIO_REGISTER;
            $model->attributes = \yii::$app->request->post();

            $currentDateTime = date('Y-m-d H:i:s');
            $model->created = $currentDateTime;
            $hash = $model->gethashSSHA($model->virtual_password);
            $encrypted_password = $hash["encrypted"]; // encrypted password
            $salt = $hash["salt"];
            $model->profile_salt = $salt;
            $model->profile_password = $encrypted_password;
            if ($model->validate() && $model->save()) {
                $user_obj = Users::find()
                    ->where(['username' => $model->username])
                    ->andWhere('profile_password = :profile_password', [':profile_password' => $model->profile_password])
                    ->one();
                return array('status' => true, 'data' => $user_obj);
            } else {
                return array('status' => false, 'data' => $model->getErrors());
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $users = new Users();
            $users->scenario = Users::SCENARIO_LOGIN;
            $users->attributes = \yii::$app->request->post();
            if ($users->validate()) {
                $user_obj = Users::find()
                   // ->select(['u_id','username','blood_name','phone','profile_salt','profile_password','created','facebook_id','profile_token'])
                    ->where(['username' => $users->virtual_user])
                    ->one();
                if (count($user_obj) > 0) {
                    $salt = $user_obj->profile_salt;
                    $encrypted_password = $user_obj->profile_password;
                    $hash = $users->checkhashSSHA($salt, $users->virtual_password);
                    if ($encrypted_password == $hash) {
                        $user_obj->profile_token = $users->profile_token;
                        $user_obj->virtual_password = "virtual";
                        $user_obj->save();
                        return array('status' => true, 'data' => $user_obj);
                    } else {
                        return array('status' => false, 'data' => 'Login Invalid');
                    }
                } else {
                    return array('status' => false, 'data' => 'No Users Found');
                }
            } else {
                return array('status' => false, 'data' => $users->getErrors());
            }
        } else {
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

    public function actionUpdate_token()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $users = new Users();
            $users->scenario = Users::SCENARIO_UPDATE_TOKEN;
            $users->attributes = \yii::$app->request->post();
            if ($users->validate()) {
                $user_obj = Users::find()
                    ->where(['username' => $users->virtual_user])
                    ->one();
                if (count($user_obj) > 0) {
                    $user_obj->profile_token = $users->profile_token;
                    $user_obj->virtual_password = "virtual";
                    $user_obj->save();
                    return array('status' => true, 'data' => 'Update Success');
                }else{
                    return array('status' => false, 'data' => 'No Users Found');
                }
            }else{
                return array('status' => false, 'data' => $users->getErrors());
            }
        }else{
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

    public function actionRemove_token()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            $users = new Users();
            $users->scenario = Users::SCENARIO_REMOVE_TOKEN;
            $users->attributes = \yii::$app->request->post();
            if ($users->validate()) {
                $user_obj = Users::find()
                    ->where(['username' => $users->virtual_user])
                    ->one();
                if (count($user_obj) > 0) {
                    $user_obj->profile_token = "";
                    $user_obj->virtual_password = "virtual";
                    if ($user_obj->validate()) {
                        if($user_obj->save()){
                            return array('status' => true, 'data' => 'Logout Success');
                        }else{
                            return array('status' => false, 'data' => 'Logout Failed');
                        }
                    } else {
                        return array('status' => false, 'data' => $user_obj->getErrors());
                    }
                }else{
                    return array('status' => false, 'data' => 'No Users Found');
                }
            }else{
                return array('status' => false, 'data' => $users->getErrors());
            }
        }else{
            return array('status' => false, 'data' => 'Permission Denied');
        }
    }

}
