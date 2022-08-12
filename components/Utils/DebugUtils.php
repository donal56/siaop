<?php 
namespace app\components\Utils;

use Yii;
use DateTime;
use app\components\FirebaseManager;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\log\Logger;
use yii\web\UploadedFile;

class DebugUtils {
	
	/**
     * Log de pruebas
     */
    public static function log($body, $title = "Test") {
        $msg = ArrayHelper::isTraversable($body) ? print_r($body, true) : var_export($body, true);
        Yii::getLogger()->log("$title: " . $msg, Logger::LEVEL_INFO, 'TestLogger');
    }
	
	/**
     * Log de errores de correo
     */
	public static function logMailError(string $plantilla, string $correo, $throwable = null) {
        $error_desc = isset($throwable) ? ' (' . $throwable->getMessage() . ')' : '';
        error_log('ERROR ENVIANDO PLANTILLA -' . $plantilla . '- A ' . $correo . $error_desc . " [" . date("Ymd") . "]\n", 3, __DIR__  ."/../mail/mail_errors.log");
    }

    /**
     * Modo de pruebas
     */
    public static function debugMode() {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);   
    }
	
	/**
	 * Retorna verdadero si el servidor es local o el parametro de la aplicación es develop o test
	 */
	public static function esEntornoDePruebas() {
        return Yii::$app->request->getHostName() == "localhost" || 
            StringHelper::endsWith(Yii::$app->request->getHostName(), ".test") ||
            \Yii::$app->params["mode"] == 'develop' ||
            \Yii::$app->params["mode"] == 'test';
    }

	/**
	 * Detecta si la solicitud actual viene de un navegador Firefox
	 */
    public static function isFirefox() {
        return strpos($_SERVER['HTTP_USER_AGENT'], "Firefox") !== false;
    }

   
}
