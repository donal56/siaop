<?php

namespace app\controllers;

use Yii;
use app\models\UnidadVehicular;
use app\models\UnidadVehicularSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UnidadesVehicularesController implements the CRUD actions for UnidadVehicular model.
 */
class UnidadesVehicularesController extends BaseController {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'json' => ['GET'],
                ]
            ]
        ];
    }

    /**
     * Lists all UnidadVehicular models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new UnidadVehicularSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UnidadVehicular model.
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
     * Creates a new UnidadVehicular model.
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

        $model = new UnidadVehicular(['activo' => 1]);
        
        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView($model);
            }
            
            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_unidad_vehicular]);
            }
            else {
                return $returnToView(new UnidadVehicular(['activo' => 1]), $model->placa);
            }
        }

        return $returnToView($model);
    }

    /**
     * Updates an existing UnidadVehicular model.
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
                return $this->redirect(['view', 'id' => $model->id_unidad_vehicular]);
            }
            else {
                return $returnToView(false, new UnidadVehicular(['activo' => 1]), $model->placa);
            }
        }

        return $returnToView(true, $model);
    }

    public function actionJson($id) {
        $this->response->format = Response::FORMAT_JSON;
        return $this->findModel($id);
    }

    /**
     * Deletes an existing UnidadVehicular model.
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
     * Finds the UnidadVehicular model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UnidadVehicular the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {

        $model = UnidadVehicular::find()->where([
                "id_unidad_vehicular" => $id,
                "id_empresa" => Yii::$app->user->identity->id_empresa
            ])
            ->one();

        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
