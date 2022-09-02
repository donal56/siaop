<?php

namespace app\controllers;

use Yii;
use app\components\Utils\DebugUtils;
use app\models\Dispositivo;
use app\models\VersionApp;
use webvimark\components\BaseController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

class ApiController extends BaseController {

    public $enableCsrfValidation = false;
    public $transaction = null;
    public $versionMayor = 1;
    public $versionMenor = 1;
    
    const ID_ORIGEN_API = 3;

    /**
     * @override
     * Métodos base
     */
	public function behaviors() {
		return [
            'authenticator' => [
                'class' => HttpBasicAuth::class
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [  
                    'usuario' => ['POST'],
                    'version-app' => ['GET'],
                    'version-api' => ['GET'],
                    'token-registrar' => ['POST'],
                    'notificaciones-enviar' => ['POST'],
                ]
            ]
		];
	}

    public function init() {
        parent::init();
        Yii::$app->user->enableSession = false;
        $this->response->format = Response::FORMAT_JSON;
        $this->layout = false;
        $this->transaction = null;
    }

    public function begin() {
        $this->transaction = Yii::$app->db->beginTransaction();
    }

    public function end($model, $returnModel = false) {

        if(is_a($model, 'yii\db\ActiveRecord') && $errors = $model->getErrors()) {
            $result = $errors;
            $this->response->statusCode = 409;
            Yii::error($errors, 'API');

            if(isset($this->transaction))  
                $this->transaction->rollback();
        }
        else {

            if(is_a($model, 'yii\db\ActiveRecord')) {
                $primaryKey = $model->primaryKey()[0];
            }
            else {
                $returnModel = true;
            }
            
            $result = $returnModel ? $model : $model->$primaryKey;
            $this->response->statusCode = 200;

            if(isset($this->transaction))  
                $this->transaction->commit();
        }

        return $result;
    }

    /**
     * API que retorna información del usuario
     * @api
     */ 
    public function actionUsuario() {
        $user = ArrayHelper::toArray(Yii::$app->user->identity, [
            "webvimark\modules\UserManagement\models\User" => [
                "id",
                "username",
                "status",
                "created_at",
                "email",
                "nombre",
                "apellido_paterno",
                "apellido_materno",
                "telefono",
                "curp",
                "puesto",
                "area",
                "numero_interno",
                "id_empresa"
            ]
        ]);
        return $this->end($user, true);
    }

    /**
     * API que consulta la ultima versión de la app
     * @api
     */ 
    public function actionVersionApp() {
        $version = VersionApp::find()->orderBy([
            'version_mayor' => SORT_DESC,
            'version_menor' => SORT_DESC
        ])->one();
        return $this->end($version, true);
    }

    /**
     * API que consulta la versión de la misma
     * @api
     */ 
    public function actionVersionApi() {
        $version = new VersionApp();
        $version->id_version_app = 0;
        $version->version_mayor = $this->versionMayor;
        $version->version_menor = $this->versionMenor;
        $version->url = Yii::$app->params['siteName'];
        $version->fecha = date('Y-m-d H:i:s');
        return $this->end($version, true);
    }

    /**
     * API que registra el token para notificaciones de la app móvil
     * @api
     * @POST String token
     */ 
    public function actionTokenRegistrar() {

        $dispositivo = new Dispositivo();
        $token = Yii::$app->request->post('token');

        if(empty($token)) {
            $dispositivo->addError('token', 'Párametro requerido o no válido');
            return $this->end($dispositivo);
        }

        $dispositivo = Dispositivo::findOne(['usuario' => Yii::$app->user->identity->id]);

        if(!isset($dispositivo)) {
            $dispositivo = new Dispositivo();
            $dispositivo->usuario = Yii::$app->user->identity->id;
        }

        $dispositivo->token = $token;

        $dispositivo->save();
        return $this->end($dispositivo);
    }

    /**
     * API que envia una notificacion a traves de firebase
     * @api
     * @POST String titulo
     * @POST String mensaje
     * @POST int destinatario
     */
    public function actionNotificacionesEnviar($debug = false) {

        if(DebugUtils::esEntornoDePruebas() && !$debug) {
            return $this->end(['dev' => 'Entorno de pruebas']);
        }

        $apiUrl     =   'https://fcm.googleapis.com/fcm/send';
        $apiKey     =   Yii::$app->params['firebaseApiKey'];
        $headers    =   ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];

        $titulo  = Html::decode(Yii::$app->request->post('titulo'));
        $mensaje = Html::decode(Yii::$app->request->post('mensaje'));
        $destinatario = Html::decode(Yii::$app->request->post('destinatario'));

        if(empty($titulo)) {
            return $this->end(['titulo' => 'Párametro requerido o inválido' ]);
        }

        if(empty($mensaje)) {
            return $this->end(['mensaje' => 'Párametro requerido o inválido' ]);
        }

        if(empty($destinatario)) {
            return $this->end(['destinatario' => 'Párametro requerido o inválido' ]);
        }

        $notification = [ 
            'notification' => [
                'title' => $titulo, 
                'body' => $mensaje, 
                'icon' => 'myIcon', 
                'sound' => 'mySound'
            ], 
            'data' => [
                "message" => [
                    'title' => $titulo, 
                    'body' => $mensaje, 
                    'icon' => 'myIcon', 
                    'sound' => 'mySound'
                ], 
                "moredata" => 'dd'
            ]
        ];

        $dispositivo = Dispositivo::findOne(['usuario' => $destinatario]);

        if(!isset($dispositivo)) {
            $dispositivo = new Dispositivo();
            $dispositivo->addError('id_dispositivo', 'Dispositivo no registrado en el socio comercial');
            return $this->end($dispositivo);
        }
        
        if(empty($dispositivo->token)) {
            $dispositivo->addError('token', 'Token no válido');
            return $this->end($dispositivo);
        }

        $notification['to'] = $apiKey;

        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $apiUrl);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($notification));
        curl_exec($request);
        curl_close($request);

        return $this->end(true);
    }
}
