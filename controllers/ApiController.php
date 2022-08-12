<?php

namespace app\controllers;

use Yii;
use app\components\Utilidades;
use app\models\Dispositivo;
use app\models\VersionApp;
use webvimark\components\BaseController;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

class ApiController extends BaseController {

    public $enableCsrfValidation = false;
    public $transaction = null;
    public $version = 1.0;
    
    const ID_ORIGEN_API = 3;

    /**
     * @override
     * Métodos base
     */
	public function behaviors() {
		return [
            'authenticator' => [
                'class' => HttpBasicAuth::class,
                'except' => ['version-app', 'version-api']
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
     * API que consulta la ultima versión de la app
     * @api
     */ 
    public function actionVersionApp() {
        $version = VersionApp::find()->orderBy(['num_version' => SORT_DESC])->one();
        return $this->end($version, true);
    }

    /**
     * API que consulta la versión de la misma
     * @api
     */ 
    public function actionVersionApi() {
        $version = new VersionApp();
        $version->id_version = 0;
        $version->num_version = $this->version;
        $version->link = Yii::$app->params['siteName'];
        $version->fecha = date('Y-m-d H:i:s');
        return $this->end($version, true);
    }

    /**
     * API que registra el token para notificaciones de la app móvil
     * @api
     * @POST String dis_token
     */ 
    public function actionTokenRegistrar() {

        $dispositivo = new Dispositivo();
        // TODO: Cambio temporal mientras se estabiliza el uso de este servicio
        $token = Yii::$app->request->get('dis_token', Yii::$app->request->post('dis_token'));

        if(empty($token)) {
            $dispositivo->addError('dis_token', 'Párametro requerido o no válido');
            return $this->end($dispositivo);
        }

        $dispositivo = Dispositivo::findOne([
            'dis_user_id' => Yii::$app->user->identity->id,
            'dis_id_empresa' => Yii::$app->user->getEmpresaPredeterminada()
        ]);

        if(!isset($dispositivo)) {
            $dispositivo = new Dispositivo();
            $dispositivo->dis_user_id = Yii::$app->user->identity->id;
            $dispositivo->dis_id_empresa = Yii::$app->user->getEmpresaPredeterminada();
        }

        $dispositivo->dis_token = $token;

        $dispositivo->save();
        return $this->end($dispositivo);
    }

    /**
     * API que envia una notificacion a traves de firebase
     * @api
     * @GET String titulo
     * @GET String mensaje
     * @GET int|null destinatario - Si este campo es nulo se envia a todos los administradores del socio comercial
     */
    public function actionNotificacionesEnviar($titulo, $mensaje, $destinatario = null) {

        if (Utilidades::esEntornoDePruebas()) {
            return $this->end(['dev' => 'Entorno de pruebas' ]);
        }

        $apiUrl     =   'https://fcm.googleapis.com/fcm/send';
        $apiKey     =   Yii::$app->params['firebaseApiKey'];
        $headers    =   ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];

        $titulo  = Html::decode(Yii::$app->request->get('titulo'));
        $mensaje = Html::decode(Yii::$app->request->get('mensaje'));
        $destinatario = Html::decode(Yii::$app->request->get('destinatario'));

        if(empty($titulo)) {
            return $this->end(['titulo' => 'Párametro requerido o inválido' ]);
        }

        if(empty($mensaje)) {
            return $this->end(['mensaje' => 'Párametro requerido o inválido' ]);
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

        if(!empty($destinatario)) {
            $dispositivo = Dispositivo::findOne([
                'dis_user_id' => $destinatario,
                'dis_id_empresa' => Yii::$app->user->getEmpresaPredeterminada()
            ]);

            if(!isset($dispositivo)) {
                $dispositivo = new Dispositivo();
                $dispositivo->addError('dis_id', 'Dispositivo no registrado en el socio comercial');
                return $this->end($dispositivo);
            }
            
            if(empty($dispositivo->dis_token)) {
                $dispositivo->addError('dis_token', 'Token no válido');
                return $this->end($dispositivo);
            }

            $notification['to'] = $apiKey;
        }
        else {
            $dispositivos = Dispositivo::find()
                ->joinWith([
                    'usuario',
                    'usuario.usuariosCentros',
                    'usuario.usuariosCentros.centro'
                ])
                ->where([
                    'user.use_fktipo' => 1,
                    'centros.cen_fkemp' => Yii::$app->user->getEmpresaPredeterminada()
                ]);

            $tokens = ArrayHelper::getColumn($dispositivos, 'dis_token');
            $notification['registration_ids'] = $tokens;
        }

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
