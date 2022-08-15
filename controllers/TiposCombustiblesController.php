<?php

namespace app\controllers;

use Yii;
use app\models\TipoCombustible;
use app\models\TipoCombustibleSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TiposCombustiblesController implements the CRUD actions for TipoCombustible model.
 */
class TiposCombustiblesController extends BaseController {
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
     * Lists all TipoCombustible models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new TipoCombustibleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TipoCombustible model.
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
     * Creates a new TipoCombustible model.
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

        $model = new TipoCombustible();

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if(!$model->save()) {
                    $transaction->rollBack();
                    return $returnToView($model);
                }
                
                $transaction->commit();
                
                if($createAnother == 0) {
                    return $this->redirect(['view', 'id' => $model->id_tipo_combustible]);
                }
                else {
                    return $returnToView(new TipoCombustible(), $model->tipo_combustible);
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
     * Updates an existing TipoCombustible model.
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

            try {
                if(!$model->save()) {
                    $transaction->rollBack();
                    return $returnToView(true, $model);
                }

                $transaction->commit();
                
                if($createAnother == 0) {
                    return $this->redirect(['view', 'id' => $model->id_tipo_combustible]);
                }
                else {
                    return $returnToView(false, new TipoCombustible(), $model->tipo_combustible);
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
     * Deletes an existing TipoCombustible model.
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
     * Finds the TipoCombustible model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TipoCombustible the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TipoCombustible::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
