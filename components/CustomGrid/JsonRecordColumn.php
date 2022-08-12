<?php

namespace app\components\CustomGrid;

use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class JsonRecordColumn extends Column
{
    /*
     * Arreglo de otros atributos por incluir. La sintaxis es la misma que la de las relaciones, p/e: osFkasi.asiFkest.est_latitud
     * TODO: Poder incluir un modelo entero de esta manera, en lugar de solo atributos
     */
    public $include = [];

    /*
     * !!POR IMPLEMENTAR!!!!
     * TODO: 
     *  - Poder incluir un modelo entero de esta manera, en lugar de solo atributos
     *  - Ejecutar despues de procesar include
     */
    public $exclude = [];

    private $recordColumnOptions = [
        'style' => 'visibility: hidden; display: none;'
    ];

    public function renderHeaderCell()
    {
        return Html::tag('th', '', $this->recordColumnOptions);
    }
    
    public function renderFilterCell()
    {
        return Html::tag('td', '', $this->recordColumnOptions);
    }

    public function renderFooterCell()
    {
        return Html::tag('td', '', $this->recordColumnOptions);
    }

    public function renderDataCell($model, $key, $index)
    {
        $options = $this->recordColumnOptions;
        
        $dataToEncode = ArrayHelper::toArray($model);

        // Usar recursion
        foreach ($this->include as $pathToInclude) {
            $recursiveAttrs = $this->createAttributerRecursively($pathToInclude, ArrayHelper::getValue($model, $pathToInclude));
            $dataToEncode = ArrayHelper::merge($dataToEncode, $recursiveAttrs);
        }

        // El grid puede modificar los valores mostrados
        // En ese caso, reemplazar los datos del modelo
        $gridModifiedAttributes = [];
        foreach ($this->grid->columns as $column) {
            if(is_a($column, DataColumn::class) && isset($column->attribute)) {
                $value = null;

                if ($column->value !== null) {
                    if (is_string($column->value)) {
                        $value = ArrayHelper::getValue($model, $column->value);
                    }
                    $value = call_user_func($column->value, $model, $key, $index, $column);
                } 
                elseif ($column->attribute !== null) {
                    $value = ArrayHelper::getValue($model, $column->attribute);
                }
                
                ArrayHelper::setValue($gridModifiedAttributes, $column->attribute, $value);
            }
        }
        $dataToEncode = ArrayHelper::merge($dataToEncode, $gridModifiedAttributes);

        $json = Json::htmlEncode($dataToEncode);
        $options['data-json'] = $json;

        return Html::tag('td', '', $options);
    }

    /*
     * Crea recursivamente un arreglo de llaves a profundiad en base a una cadena dividida por puntos,
     * el valor de la ultima llave sera el del segundo parametro
     * p/e createAttributerRecursively("parentKey.subKey.lastKey", "value") crea:
     *  [
     *      parentKey : [
     *          subKey : [
     *              lastKey : "value"
     *          ]
     *      ]
     */
    private function createAttributerRecursively(string $path, $value) {
        if(empty($path)) return;

        $parts = explode(".", $path);
        $currentKey = $parts[0];
        
        // Arreglo donde se guardaran los atributos
        $arr = [];
        
        // Si es la ultima parte
        if(count($parts) == 1) {
            $arr[$currentKey] = $value;
            return $arr;
        }
        // Si no
        else {
            unset($parts[0]);
            $newPath = implode(".", $parts);

            $arr[$currentKey] = $this->createAttributerRecursively($newPath, $value);
        }

        return $arr;
    }
}