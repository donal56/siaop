<?php 
namespace app\components\Utils;

use Yii;
use DateTime;
use app\components\FirebaseManager;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\log\Logger;
use yii\web\UploadedFile;

class DateUtils {

	public static function getCurrentDate($format = "Y-m-d") {
		$date = new DateTime('NOW', new \DateTimeZone('America/Mexico_City'));
		return $date->format($format);
	}

    public static function getFirstDayOfWeek() {
        $day = new DateTime('NOW', new \DateTimeZone('America/Mexico_City'));
        $day->setISODate((int)$day->format('o'), (int)$day->format('W'), 1);
        return $day;
    }
    
    public static function getLastDayOfWeek() {
        $day = new DateTime('NOW', new \DateTimeZone('America/Mexico_City'));
        $day->setISODate((int)$day->format('o'), (int)$day->format('W'), 7);
        return $day;
    }

    public static function getDayName($fecha) {

		$weekdayNo = date('w', strtotime($fecha));

        switch($weekdayNo) {
            case 0:
                return "Domingo";
            case 1:
                return "Lunes";
            case 2:
                return "Martes";
            case 3:
                return "Miercoles";
            case 4:
                return "Jueves";
            case 5:
                return "Viernes";
            case 6:
                return "Sabado";
        }
    }

    public static function getFriendlyDate($date) {

        $now = time();

        if($date instanceof DateTime) {
            $date = $date->format('U');
        }

        $n = $now - $date;

        if($n <= 1) {
            return 'Hace un segundo';
        }
        
        if($n < (60)) {
            return 'Hace ' . $n . ' segundos';
        }

        if($n < (60*60)) { 
            $minutes = round($n/60); 
            return 'Hace ' . $minutes . ' minuto' . ($minutes > 1 ? 's' : ''); 
        }

        if($n < (60*60*16)) { 
            $hours = round($n/(60*60)); 
            return 'Hace ' . $hours . ' hora' . ($hours > 1 ? 's' : ''); 
        }

        if($n < (time() - strtotime('yesterday'))) {
            return 'Ayer';
        }

        if($n < (60*60*24)) { 
            $hours = round($n/(60*60)); 
            return 'Hace ' . $hours . ' hora' . ($hours > 1 ? 's' : ''); 
        }

        if($n < (60*60*24*6.5)) {
            return 'Hace ' . round($n/(60*60*24)) . ' días';
        }

        if($n < (time() - strtotime('last week'))) {
            return 'La semana pasada';
        }

        if(round($n/(60*60*24*7))  == 1) {
            return 'Hace una semana';
        }

        if($n < (60*60*24*7*3.5)) {
            return 'Hace ' . round($n/(60*60*24*7)) . ' semanas';
        }

        if($n < (time() - strtotime('last month'))) {
            return 'El mes pasado';
        }

        if(round($n/(60*60*24*7*4))  == 1) {
            return 'Hace un mes';
        }

        if($n < (60*60*24*7*4*11.5)) {
            return 'Hace ' . round($n/(60*60*24*7*4)) . ' meses';
        }

        if($n < (time() - strtotime('last year'))) {
            return 'El año pasado';
        }

        if(round($n/(60*60*24*7*52)) == 1) {
            return 'Hace un año';
        }

        if($n >= (60*60*24*7*4*12)) {
            return 'Hace ' . round($n/(60*60*24*7*52)) . ' años'; 
        }

        return false;
    }
}
