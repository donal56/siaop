<?php 
namespace app\components\Utils;

class NumberUtils {
	
	/**
	 * Si el valor no es un número válido retornar cero
	 */
    public static function ifNanReturnZero($value) {
        $number = floatval($value);
        return is_nan($number) ? 0 : $number;
    }
}
