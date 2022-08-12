<?php 
namespace app\components\Utils;

use Yii;
use DateTime;
use app\components\FirebaseManager;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\log\Logger;
use yii\web\UploadedFile;

class StringUtils {
	
	/**
     * Retorna verdadero si la cadena termina con cierta subcadena
     * @param string haystack - cadena a comparar
     * @param string needle - subcadena a comparar
     * @return bool - resultado
     */
    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        return $length > 0 ? substr($haystack, -$length) === $needle : true;
    }

    /**
     * Recupera la cadena despues de la primera apararición de otra cadena
     * @param string cadena - cadena original
     * @param string despues - cadena a buscar dentro de la cadena original
     * @return string - cadena siguiente a {despues}
     */
    public static function substringAfter($cadena, $despues) {
        if(is_null($despues) || is_null($cadena)) {
            return $cadena;
        }

        $contains = strpos($cadena, $despues);
        $start = $contains !== false ? $contains + strlen($despues) : 0;
        $end = strlen($cadena);
        return substr($cadena, $start, $end);
    }

    /**
     * Recupera la cadena entre dos cadenas
     * @param string cadena - cadena original
     * @param string antes - cadena a buscar dentro de la cadena original
     * @param string despues - cadena a buscar dentro de la cadena original
     * @return string - cadena resultado
     */
    public static function substringBetween($cadena, $antes = "", $despues = "") {

        if (strpos($cadena, $antes)) { 
            $startCharCount = strpos($cadena, $antes) + strlen($antes);
            $firstSubStr = substr($cadena, $startCharCount, strlen($cadena));
            $endCharCount = strpos($firstSubStr, $despues);

            if ($endCharCount == 0) {
                $endCharCount = strlen($firstSubStr);
            }

            return substr($firstSubStr, 0, $endCharCount);
        } 
        else {
            return '';
        }
    }
	
	/**
     * Recupera una cadena aleatoria
     * @param int length - tamaño de la cadena
     * @param bool letters - incluir letras (Mayúsculas y minúsculas)
     * @param bool numbers - incluir números
     * @return string - cadena resultado
     */
	public static function generateRandomString(int $length = 10, bool $letters = true, bool $numbers = true) {
		$characters = '';
		$randomString = '';

		if($letters)
			$characters .= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

		if($numbers)
			$characters .= "0123456789";

		$charactersLength = strlen($characters);

		if($charactersLength > 0) {
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
		}

		return $randomString;
	}
}
