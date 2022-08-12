<?php
namespace app\commands;

use Yii;
use DateTime;
use DateTimeZone;
use mysqli;
use yii\console\Controller;

class CronController extends Controller {

    public function actionBackup() {

        $MAXIMO_DIAS = 20;

        //Variables de control
        $error  =   "";
        $flag   =   1;

        try {
            //Iniciando cronómetro
            $start = time();

            //Cambiando limites
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);
    
            //Borrar archivos de hace más de n días
            $files = glob("db/Respaldos/*_auto.sql");
            $now   = time();
    
            foreach($files as $file) {
                if(is_file($file)) {
                    if($now - filemtime($file) >= 3600 * 24 * $MAXIMO_DIAS) {
                        unlink($file);
                    }
                }
            }
    
            //Creando conexión
            $host       =   str_replace("mysql:host=", "", explode(";", Yii::$app->db->dsn, 2)[0]);
            $database   =   str_replace("dbname=", "", explode(";", Yii::$app->db->dsn, 2)[1]);
    
            $db = new mysqli($host, Yii::$app->db->username, Yii::$app->db->password, $database); 
            $db->set_charset ('utf8');
            $db->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    
            //Recuperando tablas
            $tables = array();
            $result = $db->query("SHOW FULL TABLES WHERE TABLE_TYPE != 'VIEW'");
            while($row = $result->fetch_row()){
                $tables[] = $row[0];
            }
    
            //Generando SQL
            $return = "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS = 0;\n\n";
    
            foreach($tables as $table){
                $result = $db->query("SELECT * FROM $table");
                $numColumns = $result->field_count;
    
                $return .= "DROP TABLE IF EXISTS $table;";
    
                $result2 = $db->query("SHOW CREATE TABLE $table");
                $row2 = $result2->fetch_row();
    
                $return .= "\n\n" . $row2[1] . ";\n\n";
    
                for($i = 0; $i < $numColumns; $i++){
    
                    while($row = $result->fetch_row()) {
                        $return .= "INSERT INTO $table VALUES(";
    
                        for($j=0; $j < $numColumns; $j++) {
    
                            if(is_null($row[$j])) {
                                $return .= 'NULL'; 
                            }
                            else if(isset($row[$j])) { 
                                $row[$j] = addslashes($row[$j]);
                                $row[$j] = mb_ereg_replace("\n", "\\n", $row[$j]);
                                $return .= '"' . $row[$j] . '"'; 
                            } 
                            else { 
                                $return .= '""'; 
                            }
                            
                            if ($j < ($numColumns-1)) { 
                                $return.= ','; 
                            }
                        }
                        $return .= ");\n";
                    }
                }
                $return .= "\n\n\n";
            }
            $return .= "SET FOREIGN_KEY_CHECKS = 1;";
    
            //Guardando
            $date       =   new DateTime('NOW', new DateTimeZone('America/Mexico_City'));
            $filename   =   '/db/Respaldos/backup_' . $date->format('Ymd_Hi') . '_auto.sql';
            $path	=   __DIR__ . '/../' . $filename;
	        $handle     =   fopen($path, 'w+');
            fwrite($handle, $return);
            fclose($handle);
        }
        catch(\Exception $e) {
            $error = $e;
            $flag = 0;
        }

        //Guardar registro
        $end = time();
        $output = $flag === 0 ? $error : $filename . " generado en " . ($end - $start) . "s";
        echo $output;

        $log = $db->prepare("INSERT INTO cron_executions VALUES('backup', CURRENT_TIMESTAMP, ?, ?)");
        $log->bind_param("is", $flag, $output);
        $log->execute();
    }
 }
