<?php 
namespace app\components\Utils;

use Yii;
use DateTime;
use app\components\FirebaseManager;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\log\Logger;
use yii\web\UploadedFile;

class ArrayUtils {

	/**
     * Recupera la primera llave de un arreglo
     */
	public static function getFirstKey(array $arr) {
        foreach ($arr as $key => $value) { 
            return $key; 
        }
    }

	/**
     * Implementación de array_values() a profundidad
     */
    public static function array_values_recursive($array) {

       $list = array();

       foreach(array_keys($array) as $key) {
          $value = $array[$key];

          if (is_scalar($value)) {
             $list[] = $value;
          } 
          elseif (is_array($value)) {
             $list = array_merge( $list, self:: array_values_recursive($value));
          }
       }

       return $list;
    }
	
    /**
     * Remueve una llave-valor de una array a traves de un valor
     * Puede remover cero o más llaves
     */
    public static function unsetByVal(&$array, $value) {
        foreach (array_keys($array, $value, true) as $key) {
            unset($array[$key]);
        }
    }
}
