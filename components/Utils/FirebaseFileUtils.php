<?php 
namespace app\components\Utils;

use app\components\FirebaseManager;
use yii\web\UploadedFile;

class FirebaseFileUtils {

    /**
     * Sube multiples archivos a Firebase y los ata a un modelo
     * @param Model $model - Modelo
     * @param Array $data - Arreglo con  nombres de los parametros @code $_FILES como llaves y atributos del modelo como valores
     *      Si el valor termina en '[]' se interpreta como un arreglo
     * @param String baseFolder - Carpeta de guardado
     * @param Integer|null $index - Indice de la subida en caso de subir varios archivos
     * @return Array - Arreglo con los campos y su estado (1 = Subido, 0 = Sin modificar, -1 = Eliminado)
     */
    public static function uploadByModelFields($model, $data, $baseFolder, $index = null) {
        
        $estatus = [];

        foreach ($data as $fileAttr => $modelAttr) {

            $isArray = substr($modelAttr, -2) == '[]';

            if($isArray) {
                $modelAttr = substr($modelAttr, 0, -2);
                $files = $_FILES[$model->formName()]['name'][$index][$modelAttr];

                foreach($files as $index2 => $fileName) {
                    $url = self::internalUpload($baseFolder, $index, $model->formName(), $modelAttr, $modelAttr, $fileName, $index2);

                    if($url !== false) {
                        $estatus[$modelAttr][$index2] = $url === null ? -1 : 1;
                        $estatus['_urls'][$modelAttr][$index2] = $url;
                    }
                    else {
                        $estatus[$modelAttr][$index2] = 0;
                    }
                }
            }
            else {
                $url = self::internalUpload($baseFolder, $index, $model->formName(), $fileAttr, $modelAttr, $model->$modelAttr, null);
    
                if($url !== false) {
                    $model->{$modelAttr} = $url;
                    $estatus[$modelAttr] = $url === null ? -1 : 1;
                    $estatus['_urls'][$modelAttr] = $url;
                }
                else {
                    $estatus[$modelAttr] = 0;
                }
            }
        }

        return $estatus;
    }

	/**
     * Rota una imagen temporal cierta cantidad de grados
     * Si @code $grados o @code $name no son válidos, el proceso no se completa
     * @param string $name - Nombre del archivo temporal
     * @param int $grados - Número de grados a rotar
     * @param string $esPng - Es PNG, o en su defecto JPG, JPEG o JIFF
     * @returns void
     */
    public static function rotateTempFile($name, $grados = 0, $esPng = false) {

        DebugUtils::debugMode();

        if(empty($name)) return;
        if(is_nan($grados)) return;
        if($grados == 0 || $grados % 360 == 0) return;

        $imageMagikRsc = null;
        
        try {
            $imageMagikRsc = $esPng ? imagecreatefrompng($name) : imagecreatefromjpeg($name);
        }
        catch(\Exception $e) {
            $esPng = !$esPng;
            
            try {
                $imageMagikRsc = $esPng ? imagecreatefrompng($name) : imagecreatefromjpeg($name);
            }
            catch(\Exception $e2) {
                return;
            }
        }

        if($imageMagikRsc === false) die();
        $imageMagikRsc = imagerotate($imageMagikRsc, 0 - floatval($grados), 0);
        
        if($imageMagikRsc === false) die();
        $esPng ? imagepng($imageMagikRsc, $name) : imagejpeg($imageMagikRsc, $name);

        if(!empty($name)) {
            fopen($name, 'r');
            imagedestroy($imageMagikRsc);
        }
    }

    /**
     * Recupera un icono de font awesome 5 de acuerdo a una extensión de archivo
     */
    public static function getFontAwesomeIcon($extension) {

        $extension = strtolower($extension);

        $icons = [
            "pdf" => "fas fa-file-pdf",
            "doc" => "fas fa-file-word",
            "docx" => "fas fa-file-word",
            "xls" => "fas fa-file-excel",
            "xlsx" => "fas fa-file-excel",
            "ppt" => "fas fa-file-powerpoint",
            "pptx" => "fas fa-file-powerpoint",
            "zip" => "fas fa-file-archive",
            "rar" => "fas fa-file-archive",
            "jpg" => "fas fa-file-image",
            "jpeg" => "fas fa-file-image",
            "jiff" => "fas fa-file-image",
            "png" => "fas fa-file-image",
            "gif" => "fas fa-file-image",
            "webp" => "fas fa-file-image",
            "xml" => "fas fa-file-code",
            "json" => "fas fa-file-code",
            "mp3" => "fas fa-file-audio",
            "acc" => "fas fa-file-audio",
            "wav" => "fas fa-file-audio",
            "ogg" => "fas fa-file-audio",
            "mp4" => "fas fa-file-video",
            "avi" => "fas fa-file-video",
            "mkv" => "fas fa-file-video",
            "webm" => "fas fa-file-video",
            "mov" => "fas fa-file-video"
        ];

        $iconDefault = "file-alt";

        return isset($icons[$extension]) ? $icons[$extension] : $iconDefault;
    }

    /**
     * Sube un archivo a Firebase y procesar opciones de modificación. 
     * Las acciones posibles son rotar la imagen de acuerdo a un numero de grados(0, 90, 180) o eliminar la imagen(DELETED)
     * 
     * @param String $baseFolder - Carpeta base donde se guardarán los archivos
     * @param Integer|null $index - Indice de la evidencia dentro de la solicitud POST actual
     * @param String $modelName - Nombre del modelo
     * @param String $fileAttribute - Nombre del parametro de archivo en @code $_FILES actual
     * @param String $fieldAttribute - Nombre del campo del modelo sobre el que se guarda la ruta final
     * @param String $oldFieldValue - Valor anterior del campo
     * @return String|false|null - URL del archivo subido o @code false para imagen no subida
     * @param Integer|null $index2 - En caso de solicitudes complejas, si se suben archivos estructuradas como un arreglo de dos niveles
     */
    private static function internalUpload($baseFolder, $index, $modelName, $fileAttribute, $fieldAttribute, $oldFieldValue, $index2) {
        
        if(empty($baseFolder) || empty($modelName) || empty($fileAttribute) || empty($fieldAttribute) || (isset($index2) && !isset($index))) {
            throw new \Exception('Uso incorrecto de la función. Parámetros incompletos');
        }

        $fileName = '';
        $tempName = '';
        $accion  = '';
        $sugestedFileName = '';
        $baseFileName = '';
        $error = 0;
        $file = false;
        $date = DateUtils::getCurrentDate("ymdHisu");

        /**
         * Determinar información del archivo.
         * Intentar ubicarlo primero usando el nombre del modelo y luego sin el.
         * Esto debido a que las solicitudes via web y via api pueden diferir en la forma en que se envian los datos.
         */
        if(isset($index)) {
            if(isset($index2)) {
                if(isset($_FILES[$modelName])) {
                    $fileName = $_FILES[$modelName]['name'][$index][$fileAttribute][$index2];
                    $tempName = $_FILES[$modelName]['tmp_name'][$index][$fileAttribute][$index2];
                    $error = $_FILES[$modelName]['error'][$index][$fileAttribute][$index2];
                }
                if(isset($_POST[$modelName][$index][$fileAttribute][$index2])) {
                    $accion = $_POST[$modelName][$index][$fileAttribute][$index2];
                }
                $sugestedFileName = $_POST[$modelName][$index][$fieldAttribute][$index2];
            }
            else {
                if(isset($_FILES[$modelName])) {
                    $fileName = $_FILES[$modelName]['name'][$index][$fileAttribute];
                    $tempName = $_FILES[$modelName]['tmp_name'][$index][$fileAttribute];
                    $error = $_FILES[$modelName]['error'][$index][$fileAttribute];
                }
                if(isset($_POST[$modelName][$index][$fileAttribute])) {
                    $accion = $_POST[$modelName][$index][$fileAttribute];
                }
                $sugestedFileName = $_POST[$modelName][$index][$fieldAttribute];
            }
        }
        else {
            if(!isset($_FILES[$modelName]['tmp_name'][$fileAttribute])) {
                if(isset($_FILES[$fileAttribute]['tmp_name'])) {
                    $fileName = $_FILES[$fileAttribute]['name'];
                    $tempName = $_FILES[$fileAttribute]['tmp_name'];         
                    $error = $_FILES[$fileAttribute]['error'];  
                    
                    if(isset($_POST[$fieldAttribute])) {
                        $sugestedFileName = $_POST[$fieldAttribute];
                    }

                    if(isset($_POST[$fileAttribute])) {
                        $accion = $_POST[$fileAttribute];
                    }
                }
            }
            else {
                $fileName = $_FILES[$modelName]['name'][$fileAttribute];
                $tempName = $_FILES[$modelName]['tmp_name'][$fileAttribute];         
                $error = $_FILES[$modelName]['error'][$fileAttribute];         
                $sugestedFileName = $_POST[$modelName][$fieldAttribute];

                if(isset($_POST[$modelName][$fileAttribute])) {
                    $accion = $_POST[$modelName][$fileAttribute];
                }
            }
        }

        if(!empty($tempName)) {

            if($error == 0) {
                $fileName = isset($sugestedFileName) && is_string($sugestedFileName) ? $sugestedFileName : $fileName;
                $fileName = empty($fileName) ? $modelName : $fileName;
                $baseFileName =  pathinfo($fileName, PATHINFO_FILENAME);
                $file = fopen($tempName, 'r');
            }
            else {
                /* https://www.php.net/manual/en/features.file-upload.errors.php */
                switch ($error) {
                    case 1:
                    case 2:
                        throw new \Exception('El archivo excede el tamaño máximo permitido. El tamaño máximo permitido es de ' . ini_get('upload_max_filesize'));
                    case 3:
                        throw new \Exception('El archivo no fue subido correctamente. Por favor intente de nuevo.');
                    case 4:
                        return false;
                    case 6:
                    case 7:
                    case 8:
                        throw new \Exception("Error interno al guardar archivo. Contacte al administrador o intente más tarde (Código $error).");
                }
            }

        }
    
        if($file === false) {

            if($accion == 'DELETED') {
                FirebaseManager::delete($oldFieldValue);
                return null;
            }
            else if(empty($oldFieldValue)) {
                return null;
            }
            else if(!empty($accion)) {

                $oldFieldValue = str_replace(" ", "%20", $oldFieldValue);

                $originalFileName   =   basename($oldFieldValue);
                $originalFileName   =   explode("-", $originalFileName)[1];
                $tempName           =   tempnam("/tmp", "bmx_");

                file_put_contents($tempName, file_get_contents($oldFieldValue));

                $ogFile = new UploadedFile([
                    'tempResource' => $tempName,
                    'name' => $tempName,
                    'tempName' => $tempName,
                ]);
                
                $esPng = StringUtils::endsWith($originalFileName, ".png");
                self::rotateTempFile($tempName, intval($accion), $esPng);
                
                if(!empty($ogFile->tempName)) {
                    $fileObj = fopen($ogFile->tempName, 'r');
                    $extension = $esPng ? "png" : "jpg";
                    return FirebaseManager::upload("{$baseFolder}/{$modelName}/{$date}-{$baseFileName}.{$extension}", $fileObj);
                }
            }
            
            // La imagen no se modifico
            return false;
        }
        else {
            $esPng = StringUtils::endsWith($fileName, ".png");
            $extension = $esPng ? "png" : "jpg";
            self::rotateTempFile($tempName, intval($accion), $esPng);
            return FirebaseManager::upload("{$baseFolder}/{$modelName}/{$date}-{$baseFileName}.{$extension}", $file);
        }
    }
}
