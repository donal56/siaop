<?php

namespace app\components;

use Yii;

class ExcelMapper {

    private $conf;
    private $className;

    private $hasCabeceras = false;
    
    /*
     * La configuración es un arreglo con los attributos como llaves y un arreglo de validaciones como llave.
     * Las validaciones pueden ser max, default, type, required.
     *      p/e 
     *          [
     *              'est_colonia' => ['max' => 50],
     *              'est_fkcom' => ['default' => 1, 'type' => 'int', 'required' => true]
     *          ]
     */
    public function __construct($conf, $className) {
        $this->conf = $conf;
        $this->className = $className;
    }
    
    /*
     * El archivo tiene o no una primera linea que contiene las cabeceras de una tabla
     */
    public function setHasCabeceras($hasCabeceras) {
        $this->hasCabeceras = $hasCabeceras;
    }

    /*
     * Lee un archivo xls o xlsx y mapea cada fila de acuerdo a las reglas de conf y nombre de clase del modelo provistas en el constructor.
     * @param $file - String: Ruta del archivo
     * @param $ordenColumnas - Array: attributos a leer del modelo, en el orden en que se leeran del archivo. Si el attributo no aparece dentro de la configuración, se omitira
     */
    public function parse(String $file, array $ordenColumnas = []) {
        require_once __DIR__ . '/../vendor/shuchkin/simplexlsx/src/SimpleXLSX.php';

        $models = [
            'valid' => [],
            'invalid' => []
        ];
        
        if($xlsx = \SimpleXLSX::parse($file)) {

            $flag = false;

            foreach($xlsx->rows(0) as $r) {

                if($this->hasCabeceras && !$flag) {
                    $flag = true;
                    continue;
                }

                $model = Yii::createObject($this->className);
                $exito = true;

                for($i = 0; $i < sizeof($ordenColumnas); $i++) {
                    $attr = $ordenColumnas[$i];
                    $validations = $this->conf[$attr];

                    if(isset($validations)) {
                        $exitoAttr = $this->processValidations($model, $attr, $r[$i], $validations);
                        $exito = $exito && $exitoAttr;
                    }
                }
                
                if($exito) {
                    $models['valid'][] = $model;
                }
                else {
                    $models['invalid'][] = $model;
                }
            }
        }
        else {
            echo \SimpleXLSX::parseError();
            die();
        }

        return $models;
    }

    private function processValidations($model, $attribute, $value, $validations) {
        
        $value = is_string($value) ? trim($value) : $value;
        $value = empty($value) ? null :  $value;

        if(isset($validations['default'])) {
            if(!isset($value) || trim($value) == "") {
                $value = $validations['default'];
            }
            else {
                $value = null;
            }
        }

        if(isset($validations['required'])) {
            if(empty($value) && $value !='0') {
                $model->$attribute = $value;
                return false;
            }
        }

        if(isset($validations['max'])) {
            $maxLength = $validations['max'];

            if(strlen(strval($value)) > $maxLength) {
                $value = substr(strval($value), 0, $maxLength);
            }
        }

        if(isset($validations['type'])) {

            $type = strtolower($validations['type']);

            switch($type) {
                case 'int':
                    $filteredValue = filter_var($value, FILTER_VALIDATE_INT);

                    if($filteredValue === false) {
                        $model->$attribute = $value;
                        return false;
                    }
            }
        }
        else {
            $value = strval($value);
        }

        $model->$attribute = $value;
        return true;
    }
}