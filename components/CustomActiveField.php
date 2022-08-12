<?php

namespace app\components;

use yii\helpers\Html;
use yii\bootstrap5\ActiveField;

class CustomActiveField extends ActiveField {
    
    public function label($label = null, $options = []) {
        
        if($label === false) {
            $this->parts['{label}'] = '';
            return $this;
        }

        $options = array_merge($this->labelOptions, $options);
        if($label !== null) {
            $options['label'] = $label;
        }
        if(!isset($options['label'])) {
            $options['label'] = Html::encode($this->model->getAttributeLabel($this->attribute));
        }

        if($this->model->isAttributeRequired($this->attribute)) {
            $options['label'] = "<strong>" . $options['label'] . "</strong>";
        }

        $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $options);

        return $this;
    }
}
