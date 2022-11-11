<?php
namespace app\components\validators;

use yii\validators\Validator;

class LongitudValidator extends Validator {

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute) {
        
        $valido = $model->$attribute == null || 
                                (is_numeric($model->$attribute) && abs($model->$attribute) <= 180);
        
        if(!$valido) {
            $this->addError($model, $attribute, 'Longitud no válida');
        }
    }
}
