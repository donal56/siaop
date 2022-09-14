<?php

namespace app\controllers;

use Yii;
use app\models\TipoUnidadVehicular;
use app\models\TipoUnidadVehicularSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TiposUnidadesVehicularesController implements the CRUD actions for TipoUnidadVehicular model.
 */
class TiposUnidadesVehicularesController extends BaseController {
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
     * Lists all TipoUnidadVehicular models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new TipoUnidadVehicularSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TipoUnidadVehicular model.
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
     * Creates a new TipoUnidadVehicular model.
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

        $model = new TipoUnidadVehicular(['activo' => 1]);
        
        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView($model);
            }
            
            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_tipo_unidad_vehicular]);
            }
            else {
                return $returnToView(new TipoUnidadVehicular(['activo' => 1]), $model->tipo_unidad_vehicular);
            }
        }

        return $returnToView($model);
    }

    /**
     * Updates an existing TipoUnidadVehicular model.
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
                return $this->redirect(['view', 'id' => $model->id_tipo_unidad_vehicular]);
            }
            else {
                return $returnToView(false, new TipoUnidadVehicular(['activo' => 1]), $model->tipo_unidad_vehicular);
            }
        }

        return $returnToView(true, $model);
    }

    /**
     * Deletes an existing TipoUnidadVehicular model.
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
     * Finds the TipoUnidadVehicular model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TipoUnidadVehicular the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TipoUnidadVehicular::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
