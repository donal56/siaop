<?php

namespace app\controllers;

use Yii;
use app\models\ClaseVehicular;
use app\models\ClaseVehicularSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClasesVehicularesController implements the CRUD actions for ClaseVehicular model.
 */
class ClasesVehicularesController extends BaseController {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ]
            ]
        ];
    }

    /**
     * Lists all ClaseVehicular models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new ClaseVehicularSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClaseVehicular model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ClaseVehicular model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($createAnother = 0) {

        $returnToView = function ($model, $guardado = null) {
            return $this->render('create', [
                'model' => $model,
                'guardado' => $guardado
            ]);
        };

        $model = new ClaseVehicular(['activo' => 1]);
        
        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView($model);
            }
            
            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_clase_vehicular]);
            }
            else {
                return $returnToView(new ClaseVehicular(['activo' => 1]), $model->clase_vehicular);
            }
        }

        return $returnToView($model);
    }

    /**
     * Updates an existing ClaseVehicular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $createAnother = 0) {

        $returnToView = function ($update, $model, $guardado = null) {
            return $this->render($update ? 'update' : 'create', [
                'model' => $model,
                'guardado' => $guardado
            ]);
        };

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))  {

            $transaction = Yii::$app->db->beginTransaction();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView(true, $model);
            }

            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_clase_vehicular]);
            }
            else {
                return $returnToView(false, new ClaseVehicular(['activo' => 1]), $model->clase_vehicular);
            }
        }

        return $returnToView(true, $model);
    }

    /**
     * Deletes an existing ClaseVehicular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the ClaseVehicular model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClaseVehicular the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ClaseVehicular::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
