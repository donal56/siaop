<?php

namespace app\controllers;

use app\components\FirebaseManager;
use Yii;
use app\components\utils\DebugUtils;
use app\components\utils\FirebaseFileUtils;
use app\components\utils\StringUtils;
use app\models\Archivo;
use app\models\Dispositivo;
use app\models\OrdenServicio;
use app\models\OrdenServicioActividad;
use app\models\OrdenServicioActividadSearch;
use app\models\OrdenServicioArchivo;
use app\models\OrdenServicioArchivoSearch;
use app\models\OrdenServicioSearch;
use app\models\Seguimiento;
use app\models\TipoArchivo;
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
                    'ordenes-servicio-operador' => ['GET'],
                    'ordenes-servicio-actividades' => ['GET'],
                    'ordenes-servicio-actividades-insert' => ['POST'],
                    'ordenes-servicio-actividades-update' => ['PUT'],
                    'ordenes-servicio-actividades-delete' => ['DELETE'],
                    'ordenes-servicio-evidencias-insert' => ['POST'],
                    'ordenes-servicio-evidencias-delete' => ['DELETE'],
                    'ordenes-servicio-evidencias' => ['GET'],
                    'ordenes-servicio-estatus-save' => ['PUT'],
                    'seguimiento-save' => ['POST']
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

    public function end(mixed $model, bool $returnModel = false, $returnInstead = null) {

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
            
            $result = $returnModel ? $model : (isset($returnInstead) ? $returnInstead : $model->$primaryKey);
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
    public function actionNotificacionesEnviar(bool $debug = false) {

        if(DebugUtils::esEntornoDePruebas() && !$debug) {
            return $this->end(['dev' => 'Entorno de pruebas']);
        }

        $apiUrl     =   'https://fcm.googleapis.com/fcm/send';
        $apiKey     =   Yii::$app->params['firebaseApiKey'];
        $headers    =   [
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        ];

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
            'time_to_live' => 600
        ];

        $dispositivo = Dispositivo::findOne(['usuario' => $destinatario]);

        if(!isset($dispositivo)) {
            $dispositivo = new Dispositivo();
            $dispositivo->addError('id_dispositivo', 'El usuario no se ha registrado en la aplicación');
            return $this->end($dispositivo);
        }
        
        $notification['to'] = $dispositivo->token;

        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $apiUrl);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($notification));
        
        $content = curl_exec($request);
        curl_close($request);

        return $this->end($content);
    }

    /**
     * API que retorna las ordenes de servicio asignadas a un operador
     * @api
     */ 
    public function actionOrdenesServicioOperador() {

        $searchModel = new OrdenServicioSearch();
        $searchParams = Yii::$app->request->queryParams;
        $searchParams["usuario_jefe_cuadrilla"] = Yii::$app->user->identity->id;

        $dataProvider = $searchModel->search($searchParams, '');
        $dataProvider->pagination->pageSize = 20;
        
        $servicios = $dataProvider->getModels();
        return $this->end($servicios);
    }

    /**
     * API que retorna las actividades de una orden de servicio
     * @api
     * @param Integer idOrdenServicio - ID de la orden de servicio
     */ 
    public function actionOrdenesServicioActividades(int $idOrdenServicio) {

        if(OrdenServicio::findOne($idOrdenServicio) == null) {
            $ordenServicio = new OrdenServicio();
            $ordenServicio->addError('id_orden_servicio', 'Servicio inexistente');
            return $this->end($ordenServicio);
        }
        
        $searchModel = new OrdenServicioActividadSearch();
        $searchParams = ["id_orden_servicio" => $idOrdenServicio];

        $dataProvider = $searchModel->search($searchParams, '');
        $dataProvider->pagination = false;
        
        $serviciosActividades = $dataProvider->getModels();
        return $this->end($serviciosActividades);
    }

    /**
     * API que registra una actividad asignada a una orden de servicio
     * @api
     */ 
    public function actionOrdenesServicioActividadesInsert() {

        $ordenServicioActividad = new OrdenServicioActividad();
        $ordenServicioActividad->load(Yii::$app->request->getBodyParams(), '');

        $ordenServicioActividad->id_orden_servicio_actividad = null;
        $ordenServicioActividad->insert();

        return $this->end($ordenServicioActividad);
    }

    /**
     * API que actualiza una actividad asignada a una orden de servicio
     * @api
     * @param idOrdenServicioActidad - ID del servicio-actividad
     */ 
    public function actionOrdenesServicioActividadesUpdate(int $idOrdenServicioActidad) {

        $ordenServicioActividad = OrdenServicioActividad::findOne($idOrdenServicioActidad);
        
        if($ordenServicioActividad == null) {
            $ordenServicioActividad = new OrdenServicioActividad();
            $ordenServicioActividad->addError('id_orden_servicio_actividad', 'Actividad-Servicio inexistente');
            return $this->end($ordenServicioActividad);
        }
        
        $ordenServicioActividad->load(Yii::$app->request->getBodyParams(), '');
        $ordenServicioActividad->id_orden_servicio_actividad = $idOrdenServicioActidad;
        $ordenServicioActividad->update();

        return $this->end($ordenServicioActividad);
    }

    /**
     * API que desvincula una actividad asignada a una orden de servicio
     * @api
     * @param idOrdenServicioActidad - ID del servicio-actividad
     */ 
    public function actionOrdenesServicioActividadesDelete(int $idOrdenServicioActidad) {

        $ordenServicioActividad = OrdenServicioActividad::findOne($idOrdenServicioActidad);
        
        if($ordenServicioActividad == null) {
            $ordenServicioActividad = new OrdenServicioActividad();
            $ordenServicioActividad->addError('id_orden_servicio_actividad', 'Actividad-Servicio inexistente');
            return $this->end($ordenServicioActividad);
        }
        
        $ordenServicioActividad->delete();

        return $this->end($ordenServicioActividad);
    }

    /**
     * API que agrega una evidencia a una orden de servicio
     * @api
     */ 
    public function actionOrdenesServicioEvidenciasInsert() {

        // Configuración del entorno
        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '10M');
        ini_set('max_input_time', 120);
        ini_set('max_execution_time', 120);

        $maxSize = 1024 * 1024 * 2;

        // Entidades
        $archivo = new Archivo();
        $ordenServicioArchivo = new OrdenServicioArchivo();

        // Parámetros
        $idOrdenServicio = Yii::$app->request->post("id_orden_servicio");
        $idTipoArchivo = Yii::$app->request->post("id_tipo_archivo");
        $ubicacionX = Yii::$app->request->post("ubicacion_x");
        $ubicacionY = Yii::$app->request->post("ubicacion_y");
        $observacion = Yii::$app->request->post("observacion");
        $fileData = $_FILES["evidencia"];

        // Validaciones
        if(empty($idOrdenServicio)) {
            $ordenServicioArchivo->addError('id_orden_servicio', 'Párametro requerido');
            return $this->end($ordenServicioArchivo);
        }
        
        if(empty($idTipoArchivo)) {
            $ordenServicioArchivo->addError('id_tipo_archivo', 'Párametro requerido');
            return $this->end($ordenServicioArchivo);
        }
        
        if(empty($fileData) || $fileData["error"] !== 0) {
            $archivo->addError('id_archivo', 'No se ha cargado un archivo/fichero');
            return $this->end($archivo);
        }
        
        if(!StringUtils::startsWith($fileData["type"], "image/")) {
            $archivo->addError('mime', 'El archivo no es una imagen');
            return $this->end($archivo);
        }
        
        if($fileData["size"] > $maxSize) {
            $archivo->addError('tamanio', 'No se ha cargado un archivo/fichero');
            return $this->end($archivo);
        }

        $this->begin();
        
        // Buscar duplicados
        $url = null;
        $md5 = md5_file($fileData["tmp_name"]);
        $duplicado = Archivo::findOne(["md5" => $md5]);

        if(!isset($duplicado)) {
            
            // Cargar archivo
            FirebaseFileUtils::uploadByModelFields($archivo, ['evidencia' => 'url'], "reporte_$idOrdenServicio" );
          
            $archivo->md5 = $md5;
            $archivo->nombre = pathinfo($fileData["name"], PATHINFO_FILENAME);
            $archivo->extension = pathinfo($fileData["name"], PATHINFO_EXTENSION);
            $archivo->tamanio = $fileData["size"];
            $archivo->mime = $fileData["type"];
            $archivo->ubicacion_x = $ubicacionX;
            $archivo->ubicacion_y = $ubicacionY;
            $archivo->observacion = $observacion;
            $archivo->ip = Yii::$app->request->remoteIP;
            
            if(!$archivo->save()) {
                FirebaseManager::delete($archivo->url);
                return $this->end($archivo);
            }

            $ordenServicioArchivo->id_archivo = $archivo->id_archivo;
            $url = $archivo->url;
        }
        else {
            $ordenServicioArchivo->id_archivo = $duplicado->id_archivo;
            $url = $duplicado->url;
        }

        // Relacionar con la orden de servicio
        $ordenServicioArchivo->id_orden_servicio = $idOrdenServicio;
        $ordenServicioArchivo->id_tipo_archivo = $idTipoArchivo;
        $ordenServicioArchivo->save();

        return $this->end($ordenServicioArchivo, false, [
            'id' => $ordenServicioArchivo->id_orden_servicio_archivo,
            'url' => $url
        ]);
    }

    /**
     * API que desvincula una evidencia a una orden de servicio y la elimina
     * @api
     * @param idOrdenServicioArchivo - ID del servicio-archivo
     */ 
    public function actionOrdenesServicioEvidenciasDelete(int $idOrdenServicioArchivo) {

        $ordenServicioArchivo = OrdenServicioArchivo::findOne($idOrdenServicioArchivo);
        
        if($ordenServicioArchivo == null) {
            $ordenServicioArchivo = new OrdenServicioArchivo();
            $ordenServicioArchivo->addError('id_orden_servicio_archivo', 'Actividad-Archivo inexistente');
            return $this->end($ordenServicioArchivo);
        }
        
        $archivo = $ordenServicioArchivo->archivo;
        $ordenServicioArchivo->delete();

        // El archivo es usado en otra relación
        $eliminarArchivo = !OrdenServicioArchivo::find()->where(['id_archivo' => $archivo->id_archivo])->exists();

        if($eliminarArchivo) {
            $archivo->delete();
            FirebaseManager::delete($archivo->url);
        }

        return $this->end($ordenServicioArchivo);
    }

    /**
     * API que retorna las evidencias de cierto tipo de una orden de servicio
     * @api
     * @param Integer idOrdenServicio - ID de la orden de servicio
     * @param Integer idTipoArchivo - ID del tipo de evidencia
     */ 
    public function actionOrdenesServicioEvidencias(int $idOrdenServicio, int $idTipoArchivo) {

        if(OrdenServicio::findOne($idOrdenServicio) == null) {
            $ordenServicio = new OrdenServicio();
            $ordenServicio->addError('id_orden_servicio', 'Servicio inexistente');
            return $this->end($ordenServicio);
        }

        if(TipoArchivo::findOne($idTipoArchivo) == null) {
            $tipoArchivo = new TipoArchivo();
            $tipoArchivo->addError('id_tipo_archivo', 'Tipo de archivo inexistente');
            return $this->end($tipoArchivo);
        }
        
        $searchModel = new OrdenServicioArchivoSearch();
        $searchParams = [
            "id_orden_servicio" => $idOrdenServicio,
            "id_tipo_archivo" => $idTipoArchivo
        ];

        $dataProvider = $searchModel->search($searchParams, '');
        $dataProvider->pagination = false;
        
        $serviciosArchivos = ArrayHelper::toArray($dataProvider->getModels(), [
            "app\models\OrdenServicioArchivo" => [
                "id_orden_servicio_archivo",
                "id_orden_servicio",
                "id_archivo",
                "id_tipo_archivo",
                "validado",
                "usuario_validacion",
                "fecha_validacion",
                "fecha_version",
                "usuario_version",
                "archivo" => function($model) {
                    return $model->archivo;
                }
            ]
        ]);
        return $this->end($serviciosArchivos);
    }

    /**
     * API que guarda el seguimiento a un usuario
     * @api
     */  
    public function actionSeguimientoSave() {
        $this->begin();
        $seguimiento = new Seguimiento();
        $seguimiento->load(Yii::$app->request->getBodyParams(), '');
        $seguimiento->insert();
        return $this->end($seguimiento);
    }

    /**
     * API que actualiza el estatus de una orden de servicio
     * @api
     */  
    public function actionOrdenesServicioEstatusSave() {

        // Parámetros
        $idOrdenServicio = Yii::$app->request->getBodyParam("id_orden_servicio");
        $idEstatus = Yii::$app->request->getBodyParam("id_estatus");

        // Validaciones
        if(empty($idOrdenServicio)) {
            $ordenServicio = new OrdenServicio();
            $ordenServicio->addError('id_orden_servicio', 'Párametro requerido');
            return $this->end($ordenServicio);
        }
        
        if(empty($idEstatus)) {
            $ordenServicio = new OrdenServicio();
            $ordenServicio->addError('id_estatus', 'Párametro requerido');
            return $this->end($ordenServicio);
        }

        $ordenServicio = OrdenServicio::findOne($idOrdenServicio);

        
        if($ordenServicio == null) {
            $ordenServicio = new OrdenServicio();
            $ordenServicio->addError('id_orden_servicio', 'Servicio inexistente');
            return $this->end($ordenServicio);
        }
        $this->begin();
        $ordenServicio->id_estatus = $idEstatus;
        $ordenServicio->save();
        return $this->end($ordenServicio);
    }
}
