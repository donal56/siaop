<?php

namespace app\controllers;

use Yii;
use app\models\UnidadMedida;
use app\models\UnidadMedidaSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UnidadesMedidaController implements the CRUD actions for UnidadMedida model.
 */
class UnidadesMedidaController extends BaseController {
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
     * Lists all UnidadMedida models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new UnidadMedidaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UnidadMedida model.
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
     * Creates a new UnidadMedida model.
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

        $model = new UnidadMedida(['activo' => 1]);
        
        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if(!$model->save()) {
                $transaction->rollBack();
                return $returnToView($model);
            }
            
            $transaction->commit();
            
            if($createAnother == 0) {
                return $this->redirect(['view', 'id' => $model->id_unidad_medida]);
            }
            else {
                return $returnToView(new UnidadMedida(['activo' => 1]), $model->unidad_medida);
            }
        }

        return $returnToView($model);
    }

    /**
     * Updates an existing UnidadMedida model.
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
                return $this->redirect(['view', 'id' => $model->id_unidad_medida]);
            }
            else {
                return $returnToView(false, new UnidadMedida(['activo' => 1]), $model->unidad_medida);
            }
        }

        return $returnToView(true, $model);
    }

    /**
     * Deletes an existing UnidadMedida model.
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
     * Finds the UnidadMedida model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UnidadMedida the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = UnidadMedida::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
