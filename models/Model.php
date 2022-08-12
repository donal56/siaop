<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Model extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass - Clase con namespace de los modelos a crear
     * @param array $multipleModels - Modelos preexistentes, se reemplazaran si los IDs coinciden con los solicitados en POST
     * @param string $path - Ruta del modelo dentro de los parametros post. Separada por '.'
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [], $path = null)
    {
        $model    = new $modelClass;
        $models   = [];
        $id =  $model::primaryKey()[0];
        $post = Yii::$app->request->post();

        if($path == null) {
            $path = $model->formName();
            $post = isset($post[$path]) ? $post[$path] : [];
        }
        else {
            $post = ArrayHelper::getValue($post, $path, []);
        }

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, $id, $id));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item[$id]) && !empty($item[$id]) && isset($multipleModels[$item[$id]])) {
                    $models[] = $multipleModels[$item[$id]];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }
}
