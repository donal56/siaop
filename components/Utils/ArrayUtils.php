<?php 
namespace app\components\Utils;

class ArrayUtils {

    /**
     * Busca un elemento en un arreglo y retorna la primera coincidencia, de lo contrario [[null]]
     * La función de búsqueda toma el valor recorrido dentro del arreglo
     */
    public static function find(array $arr, \Closure $callable) {
        foreach($arr as $el) {
            $success = call_user_func($callable, $el);
            if($success) {
                return $el;
            }
        }
    }

	/**
     * Recupera la primera llave de un arreglo
     */
	public static function getFirstKey(array $arr) {
        foreach ($arr as $key => $value) { 
            return $key; 
        }
    }

    /**
     * Recupera el primer valor de un arreglo
     */
    public static function getFirstValue(array $arr) {
        foreach ($arr as $key => $value) { 
            return $value; 
        }
    }

    /**
     * Recupera la última llave de un arreglo
     */
    public static function getLastKey(array $arr) {
        $keys = array_keys($arr);
        return count($keys) > 0 ? end($keys) : null;
    }

    /**
     * Recupera el último valor de un arreglo
     */
    public static function getLastValue(array $arr) {
        return count($arr) > 0 ? end($arr) : null;
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
