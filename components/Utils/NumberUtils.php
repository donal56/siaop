<?php 
namespace app\components\Utils;

class NumberUtils {
	
	/**
	 * Si el valor no es un número válido retornar cero
	 */
    public static function ifNanReturnZero($value) {
        return is_nan($value) ? 0 : $value;
    }
}
