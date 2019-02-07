<?php

namespace app\controllers;

use Yii;
use app\models\Managers;
use app\models\ManagersSearch;
use yii\helpers\Json;
use kartik\growl\Growl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManagersController implements the CRUD actions for Managers model.
 */
class ManagersController extends Controller
{
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
     * Lists all Managers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManagersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('roles = '.Managers::ROLE_MANAGER);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Managers model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$rending = null)
    {
        if($rending === "ajax"){
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionProfile()
    {
        $request = Yii::$app->request;
        if ($request->post()){
            $key = Yii::$app->request->post('key', null);
            return $this->render('view', [
                'model' => $this->findModelWithKey($key),
            ]);
        }else{
            Yii::$app->getSession()->setFlash('manager_profile', [
                'type' =>  Growl::TYPE_DANGER,
                'duration' => 5000,
                'icon' => 'fa fa-close',
                'title' => ' Invalid URL',
                'message' => 'Sorry, the page not found',
                'positonY' => 'bottom',
                'positonX' => 'right'
            ]);
            if(Yii::$app->request->referrer){
                return $this->redirect(Yii::$app->request->referrer);
            }else{
                return $this->goHome();
            }
        }
    }

    public function actionAdmin()
    {
        Yii::$app->getSession()->setFlash('admin_profile', [
            'type' =>  Growl::TYPE_DANGER,
            'duration' => 5000,
            'icon' => 'fa fa-close',
            'title' => ' Invalid URL',
            'message' => 'Sorry, the page not found',
            'positonY' => 'bottom',
            'positonX' => 'right'
        ]);
        if(Yii::$app->request->referrer){
            return $this->redirect(Yii::$app->request->referrer);
        }else{
            return $this->goHome();
        }
    }


    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->status = Managers::STATUS_DELETED;
        $model->password = "deshario"; // Only For Validation
        if($model->save()){
            Yii::$app->getSession()->setFlash('login_success', [
                'type' =>  Growl::TYPE_DANGER,
                'duration' => 5000,
                'icon' => 'fa fa-lock fa-lg',
                'title' => $model->username.' Deactivated !',
                'message' => "Now ".$model->username." won't able to access the Cloud BloodBank anymore.",
                'positonY' => 'bottom',
                'positonX' => 'right'
            ]);
        }
        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = Managers::STATUS_ACTIVE;
        $model->password = "deshario"; // Only For Validation
        if($model->save()){
            Yii::$app->getSession()->setFlash('login_success', [
                'type' =>  Growl::TYPE_SUCCESS,
                'duration' => 5000,
                'icon' => 'fa fa-unlock fa-lg',
                'title' => $model->username.' Activated',
                'message' => "All access granted for ".$model->username.".",
                'positonY' => 'bottom',
                'positonX' => 'right'
            ]);
        }
        return $this->redirect(['index']);
    }

    /**
     * Creates a new Managers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new Managers();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->created_at = time();
            $model->updated_at = time();
            $model->manager_key = $model->getRandomKey(10);
            if($model->save()){
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Managers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())){
            $model->setPassword($model->password);
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Managers model.
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
     * Finds the Managers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Managers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Managers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelv2($id,$key){
        if($model = Managers::find()
            ->where(['id' => $id, 'manager_key' => $key])
            //->where(['manager_key' => $key])
            ->one()){
            return $model;
        };
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelWithKey($key){
        if($model = Managers::find()
            ->where(['manager_key' => $key])
            ->one()){
            return $model;
        };
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
