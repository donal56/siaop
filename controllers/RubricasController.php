<?php

namespace app\controllers;

use Yii;
use app\models\Rubrica;
use app\models\RubricaSearch;
use webvimark\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RubricasController implements the CRUD actions for Rubrica model.
 */
class RubricasController extends BaseController {
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
     * Lists all Rubrica models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new RubricaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rubrica model.
     * @param string $id_rubrica
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rubrica model.
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

        $model = new Rubrica();

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if(!$model->save()) {
                    $transaction->rollBack();
                    return $returnToView($model);
                }
                
                $transaction->commit();
                
                if($createAnother == 0) {
                    return $this->redirect(['view', 'id' => $model->id_rubrica]);
                }
                else {
                    return $returnToView(new Rubrica(), $model->rubrica);
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
     * Updates an existing Rubrica model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id_rubrica
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
                    return $this->redirect(['view', 'id' => $model->id_rubrica]);
                }
                else {
                    return $returnToView(false, new Rubrica(), $model->rubrica);
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
     * Deletes an existing Rubrica model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id_rubrica
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Rubrica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id_rubrica
     * @return Rubrica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Rubrica::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página no existe o no esta autorizado para verla');
    }
}
