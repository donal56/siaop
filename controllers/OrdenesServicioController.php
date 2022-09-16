<?php

namespace app\controllers;

use app\models\Actividad;
use app\models\Estatus;
use app\models\Model;
use Yii;
use app\models\OrdenServicio;
use app\models\OrdenServicioActividad;
use app\models\OrdenServicioSearch;
use app\models\UnidadVehicular;
use webvimark\components\BaseController;
use webvimark\modules\UserManagement\models\User;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
            'model' => OrdenServicio::findModel($id),
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

        $returnToView = function ($model, $serviciosActividades, $guardado = null) {
            $actividades = Actividad::findAll(['id_empresa' => Yii::$app->user->identity->id_empresa]);
            return $this->render('create', compact('model', 'guardado', 'serviciosActividades', 'actividades'));
        };

        $model = new OrdenServicio([
            'id_tipo_orden_servicio' => $tipo,
            'id_estatus' => Estatus::ORDEN_SERVICIO_REGISTRADO
        ]);
        $serviciosActividades = [new OrdenServicioActividad()];
        $datos = Yii::$app->request->post();
        
        if($model->load($datos)) {

            $transaction = Yii::$app->db->beginTransaction();
            $model->loadOrigen();
            $model->loadDestino();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView($model, $serviciosActividades);
            }

            $serviciosActividades = Model::createMultiple(OrdenServicioActividad::class);
            Model::loadMultiple($serviciosActividades, $datos);

            foreach ($serviciosActividades as $servicioActividad) {
                $servicioActividad->id_orden_servicio = $model->id_orden_servicio;
                $servicioActividad->realizado = 0;

                if (!$servicioActividad->save()) {
                    $transaction->rollBack();
                    return $returnToView($model, $serviciosActividades);
                }
            }
            
            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_orden_servicio]);
            }
            else {
                return $returnToView(new OrdenServicio(), [new OrdenServicioActividad()], $model->id_orden_servicio);
            }
        }

        return $returnToView($model, $serviciosActividades);
    }

    /**
     * Updates an existing OrdenServicio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id_orden_servicio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $createAnother = 0) {

        $returnToView = function ($update, $model, $serviciosActividades, $guardado = null) {
            $actividades = Actividad::findAll(['id_empresa' => Yii::$app->user->identity->id_empresa]);
            return $this->render($update ? 'update' : 'create', compact('model', 'guardado', 'serviciosActividades', 'actividades'));
        };

        $model = OrdenServicio::findModel($id);
        $serviciosActividades = $model->ordenServicioActividades;
        $datos = Yii::$app->request->post();

        if($model->load($datos)) {

            $transaction = Yii::$app->db->beginTransaction();
            $model->loadOrigen();
            $model->loadDestino();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView(true, $model, $serviciosActividades);
            }

            $pk = 'id_orden_servicio_actividad';
            $deletedIDs = array_diff_key(ArrayHelper::getColumn($serviciosActividades, $pk), ArrayHelper::getColumn($datos['OrdenServicioActividad'], $pk));
            $serviciosActividades = Model::createMultiple(OrdenServicioActividad::class, $serviciosActividades);
            Model::loadMultiple($serviciosActividades, $datos);

            if (!empty($deletedIDs)) {
                OrdenServicioActividad::deleteAll(['IN', $pk, $deletedIDs]);
            }

            foreach ($serviciosActividades as $servicioActividad) {
                $servicioActividad->id_orden_servicio = $model->id_orden_servicio;

                if($servicioActividad->isNewRecord) {
                    $servicioActividad->realizado = 0;
                }

                if (!$servicioActividad->save()) {
                    $transaction->rollBack();
                    return $returnToView(true, $model, $serviciosActividades);
                }
            }

            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_orden_servicio]);
            }
            else {
                return $returnToView(false, new OrdenServicio(), $serviciosActividades, $model->id_orden_servicio);
            }
        }

        return $returnToView(true, $model, $serviciosActividades);
    }

    /**
     * Deletes an existing OrdenServicio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id_orden_servicio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        OrdenServicio::findModel($id)->delete();
        return $this->redirect(['index']);
    }
}
