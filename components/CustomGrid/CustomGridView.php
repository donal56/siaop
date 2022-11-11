<?php

namespace app\components\customGrid;

use app\assets\CustomGridAsset;
use app\components\JsonRecordColumn;
use yii\base\InvalidConfigException;
use yii\grid\GridView; 
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;

class CustomGridView extends GridView {

    /*
     * ID unico del grid, usado para poder utilizar la API de JavaScript
     */
    public $id;

    /*
     * Arreglo de otros atributos o modelos por incluir. 
     * La sintaxis es la misma que la de las relaciones, p/e: osFkasi.asiFkest.est_latitud, osFkEstatus
     */
    public $include;
    
    /*
     * Arreglo de atributos o modelos por excluir.
     * Esta lista se aplica despues de procesar los atributos incluidos. 
     * La sintaxis es la misma que la de las relaciones, p/e: osFkasi.asiFkest.est_latitud, osFkEstatus
     */
    public $exclude;

    /*
     * Scripts a agregar despues de cargar el grid. Estos scripts se cargan tambien al cargar respuestas PJAX.
     */
    private $afterScript;

    public function init()
    {
        if(empty($this->id)) {
            throw new InvalidConfigException('Es necesario configurar un id para utlizar CustomGrid');
        }
        if(empty($this->columns)) {
            throw new InvalidConfigException('Es necesario configurar al menos una columna');
        }
        parent::init();
    }

    /**
     * CustomGridView no soporta la funcionalidad de guessColumns(), donde un grid vacio se autoconfigura de acuerdo al modelo.
     */
    protected function initColumns()
    {
        array_unshift($this->columns, [
            'class' => JsonRecordColumn::class,
            'include' => $this->include,
            'exclude' => $this->exclude,
        ]);

        for ($i= 0; $i < count($this->columns); $i++) { 

            $options = ArrayHelper::merge($this->columns[$i]->contentOptions, [
                'data-attr' => $this->columns[$i]['attribute']
            ]);

            $this->columns[$i]['contentOptions'] = $options;
        }

        parent::initColumns();
    }

    public function run()
    {
        $view = $this->getView();
        CustomGridAsset::register($view);

        $id = $this->options['id'];
        $options = Json::htmlEncode(array_merge($this->getClientOptions(), ['filterOnFocusOut' => $this->filterOnFocusOut]));

        $view->registerJs("jQuery('#$id').yiiGridView($options);");
        
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback('/{\\w+}/', function ($matches) {
                $content = $this->renderSection($matches[0]);

                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        Pjax::begin([
            'id' => $this->id,
            'timeout' => 100000,
            'enablePushState' => false,
            'linkSelector' => false,
            'formSelector' => false,
            
        ]);
        echo Html::tag($tag, $content, $options);
        echo Html::tag("script", implode("\n", $this->afterScript));
        Pjax::end();
    }

    /**
     * @param String $script - Código JS a ejecutar
     * @param String $id - Identificador de la pieza de código.
     * @param boolean $update - Si el identificador ya existe, actualizar
     */
    public function addAfterScript($script, $id, $replaceExisting = false) {

        if(empty($id)) {
            throw new InvalidConfigException('ID obligatorio');
        }

        if(isset($this->afterScript[$id]) && !$replaceExisting) {
            return;
        }

        $this->afterScript[$id] = $script;
    }

    /**
     * @param String $id - Identificador de la pieza de código
     */
    public function removeAfterScript($id) {
        if(empty($id)) {
            throw new InvalidConfigException('ID obligatorio');
        }

        unset($this->afterScript[$id]);
    }
}