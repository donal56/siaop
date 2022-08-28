<?php 
namespace app\components\Utils;

class ArrayUtils {

    /**
     * Busca un elemento en un arreglo y retorna la primera coincidencia, de lo contrario [[null]]
     * La función de búsqueda toma como parámetro el valor y llave recorrido dentro del arreglo
     */
    public static function find(array $arr, \Closure $callable) {
        foreach($arr as $key => $el) {
            $success = call_user_func($callable, $el, $key);
            if($success) {
                return $el;
            }
        }
    }

    /**
     * Mapea un arreglo hacia otro arreglo
     * La función de mapeado toma los siguientes parametros en orden:
     *  - Valor
     *  - Llave
     *  - Contador
     *  - Arreglo original
     */
    public static function map(array $arr, \Closure $callable) {
        $newArr = [];

        $counter = 0;
        foreach($arr as $key => $el) {
            $newArr[] = call_user_func($callable, $el, $key, $counter, $arr);
            $counter++;

        }

        return $newArr;
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
