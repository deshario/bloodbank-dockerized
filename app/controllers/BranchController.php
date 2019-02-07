<?php

namespace app\controllers;

use Yii;
use app\models\Branch;
use app\models\BranchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BranchController implements the CRUD actions for Branch model.
 */
class BranchController extends Controller
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
     * Lists all Branch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Branch model.
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

    public function actionViewer($code)
    {
        return $this->render('view', [
            'model' => $this->findModelByCode($code),
        ]);
    }

    /**
     * Creates a new Branch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Branch();

        if ($model->load(Yii::$app->request->post())) {
            $currentDateTime = date('Y-m-d H:i:s');
            $model->branch_created = $currentDateTime;
            $code = strtoupper(substr($model->branch_name, 0, 3)).date('dmHi');
            $model->branch_code = $code;
            $model->save();
            return $this->redirect(['view', 'id' => $model->branch_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Branch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->branch_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Branch model.
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
     * Finds the Branch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelByCode($code)
    {
        if($model = Branch::find()
            ->where(['branch_code' => $code])
            ->one()){
            return $model;
        };
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******************************  RESTFUL API FUNCTIONS *******************************/

    public function actionGet_all()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $branches = Branch::find()
            ->orderBy([
                'branch_created'=>SORT_DESC,
            ])
            ->all();
        if (count($branches) > 0) {
            return array('status' => true, 'data' => $branches);
        } else {
            return array('status' => false, 'data' => 'No Records Found');
        }
    }

}
