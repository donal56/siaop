<?php 
namespace app\components\Utils;

class StringUtils {

    public static function pluralize($word) {
     
        if(strpos($word, ' ') !== false) {
            $words = explode(' ', $word); 
            array_walk($words, function(&$word) {
                $word = StringUtils::pluralize($word);
            });
            return implode(' ', $words);
        }

        $blackList = [
            "estatus"
        ];

        if(in_array(strtolower($word), $blackList)) return $word;
        if(self::endsWith($word, 'd') || self::endsWith($word, 'n') || self::endsWith($word, 'r')) return $word . "es";
        return $word . "s";
    }


    public static function singularize($word) {

        if(strpos($word, ' ') !== false) {
            $words = explode(' ', $word); 
            array_walk($words, function(&$word) {
                $word = StringUtils::singularize($word);
            });
            return implode(' ', $words);
        }

        $blackList = [
            "estatus"
        ];

        if(in_array(strtolower($word), $blackList)) return $word;

        if(self::endsWith($word, 'des') || self::endsWith($word, 'nes') || self::endsWith($word, 'res')) {
            return substr($word, 0, -2);
        }
        else if(self::endsWith($word, 's')) {
            return substr($word, 0, -1);
        }
        else {
            return $word;
        }
    }

    public static function capitalizeWord($word) {
        return ucfirst(strtolower($word));
    }

    public static function capitalizeWords($words) {
        $parts = explode(" ", $words);
        array_walk($parts, function(&$item) {
            $item = ucfirst($item);
        });
        return implode(" ", $parts);
    }

	/**
     * Retorna verdadero si la cadena inicia con cierta subcadena
     * @param string haystack - cadena a comparar
     * @param string needle - subcadena a comparar
     * @return bool - resultado
     */
    public static function startsWith($haystack, $needle) {
        return strpos($haystack, $needle) === 0;
    }

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
