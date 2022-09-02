<?php

namespace app\controllers;

use app\models\Estatus;
use Yii;
use app\models\OrdenServicio;
use app\models\OrdenServicioSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdenesServicioController implements the CRUD actions for OrdenServicio model.
 */
class OrdenesServicioController extends BaseController {
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
     * Lists all OrdenServicio models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new OrdenServicioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrdenServicio model.
     * @param string $id_orden_servicio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OrdenServicio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param createAnother - guardar y crear otro
     * @param t - tipo de orden de servicio
     * @return mixed
     */
    public function actionCreate($tipo, $createAnother = 0) {

        $returnToView = function ($model, $guardado = null) {
            return $this->render('create', [
                'model' => $model,
                'guardado' => $guardado
            ]);
        };

        $model = new OrdenServicio([
            'id_tipo_orden_servicio' => $tipo,
            'id_estatus' => Estatus::ORDEN_SERVICIO_REGISTRADO
        ]);
        
        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            $model->loadOrigen();
            $model->loadDestino();

            try {
                if(!$model->save()) {
                    $transaction->rollBack();
                    return $returnToView($model);
                }
                
                $transaction->commit();
                
                if($createAnother == 0) {
                    return $this->redirect(['view', 'id' => $model->id_orden_servicio]);
                }
                else {
                    return $returnToView(new OrdenServicio(), $model->id_orden_servicio);
                }
            } 
            catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $returnToView($model);
    }

    /**
     * Updates an existing OrdenServicio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id_orden_servicio
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
            $model->loadOrigen();
            $model->loadDestino();

            try {
                if(!$model->save()) {
                    $transaction->rollBack();
                    return $returnToView(true, $model);
                }

                $transaction->commit();
                
                if($createAnother == 0) {
                    return $this->redirect(['view', 'id' => $model->id_orden_servicio]);
                }
                else {
                    return $returnToView(false, new OrdenServicio(), $model->id_orden_servicio);
                }
            } 
            catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $returnToView(true, $model);
    }

    /**
     * Deletes an existing OrdenServicio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id_orden_servicio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the OrdenServicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id_orden_servicio
     * @return OrdenServicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = OrdenServicio::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
