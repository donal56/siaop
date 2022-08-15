<?php

namespace app\controllers;

use app\models\Parametro;
use Yii;
use app\models\Parametros;
use yii\helpers\Url;
use yii\web\Response;
use webvimark\components\BaseController;
use webvimark\modules\UserManagement\models\forms\LoginForm;

class SiteController extends BaseController {

    public $freeAccessActions = ['swlogin','logout', 'download', 'index'];

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        if(Yii::$app->user->isGuest) {
            return $this->redirect("/user-management/auth/login");
        }
        else {
            return $this->render('dashboard', compact('model'));
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
		
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSwlogin() {
        $model = new LoginForm();
        $model->username = Yii::$app->request->get('user');
        $model->password = Yii::$app->request->get('pass');
        
        if ($model->login()) {
            $user = Yii::$app->user->identity;
            return json_encode([
                'id' => $user->id, 
                'nombre' => $user->use_nombre, 
                'tipo' => $user->use_fktipo,
                'rol' => "",
                'parametros' => Parametro::recuperarParametrosDeLaEmpresa(null, true),
            ]);
        }
        else{
            return json_encode(['error' => 'Usuario y/o contraseña incorrecto']);
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Vista al entrar en modo de mantenimiento
     */
    public function actionMantenimiento() {
        $this->layout = false;
        return $this->render('mantenimiento');
    }

    public function actionDownload() {
        // header("Content-disposition: attachment; filename= dc-app.apk; Content-type: application/vnd.android.package-archive");
        // readfile(Url::to('@web/apk/v1.0.apk', true));
        return $this->redirect(Url::to('@web/apk/app.apk', true));
    }

    /**
    * La última vez la migración tuvo problemas con php-fpm, el cual no existe en windows, 
    * por lo que la migración se realizó desde el servidor.
    */
    public function actionMigrate($file) {
        if(Yii::$app->user->isSuperAdmin && date("Y-m-d") === "2021-03-15") {
            $path = Yii::getAlias("@app/db/Migraciones/" . $file . ".php");
            echo "Ejecutando " . $path . "<br><br>";
            require($path);
        }
        else {
            echo "Para realizar esta acción debe ser superadmin, ajustar la fecha actual en la acción del controlador y configurar los parametros de la migración.";
        }
        die();
    }

    /**
     * Libera la cache de la aplicación
     */
    public function actionFlush() {

        if(!Yii::$app->user->isSuperAdmin) return;

        echo "Limpiando cache del servidor...";
        Yii::$app->cache->flush();
        Yii::$app->fileCache->flush();
    }

    /**
     * Vista de parametros
     *
     * @return string
     */
    public function actionParametros() {
        return $this->render("parametros");
    }
}
