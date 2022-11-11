<?php
namespace app\components\validators;

use yii\validators\Validator;

class LatitudValidator extends Validator {

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute) {

        $valido = $model->$attribute == null || 
                                (is_numeric($model->$attribute) && abs($model->$attribute) <= 90);
        
        if(!$valido) {
            $this->addError($model, $attribute, 'Latitud no válida');
        }
    }
}
