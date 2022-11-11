<?php

namespace app\components\customGrid;

use Yii;
use webvimark\components\StatusColumn;

class StatusColumnCustom extends StatusColumn {
	
	protected $_titles =[];
	protected $_cellHTMLContent =[];

	/**
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function initOptions() {
		
		$this->checkOptionsArray();
		$this->setCellStyleOptions();

		$this->format = 'raw';

		foreach ($this->optionsArray as $option) {
			
			$this->filter[$option[0]] = $option[3];
			$this->_labelClasses[$option[0]] = $option[2];
			$this->_titles[$option[0]] = $option[3];
			$this->_cellHTMLContent[$option[0]] = $option[1];
		}

        if ($this->value instanceof \Closure) {
            $userFunc = $this->value;
        } 
		else {
            $userFunc = function ($model, $key, $index, $widget) {
                return $model->{$this->attribute};
            };
        }
		
        $this->value = function ($model, $key, $index, $widget) use ($userFunc) {
            $attributeValue = call_user_func($userFunc, $model, $key, $index, $widget);

			if ( isset($widget->_labelClasses[$attributeValue], $widget->filter[$attributeValue], $widget->_titles[$attributeValue]) ) {
				
				$label = $widget->_labelClasses[$attributeValue];
				$title = $widget->_titles[$attributeValue];

				$class = ($label === false) ? '' : "label label-{$label}";
				$value = $widget->_cellHTMLContent[$attributeValue];

				$style = ($label === false) ? '' : 'font-size:85%;';
				$data = '';

				if ( ! empty($this->toggleUrl) ) {
					
					$style .= 'cursor:pointer;';

					preg_match('/=_\w+_/',$this->toggleUrl, $matches);

					$idAttributePlaceholder = ltrim($matches[0], '=');
					$idAttribute = trim($idAttributePlaceholder, '_');

					$toggleUrl = str_replace($idAttributePlaceholder, $model->{$idAttribute}, $this->toggleUrl);

					$dataType = empty($this->pjaxId) ? 'grid-toggle' : 'grid-toggle-pjax';
					$data .= "data-type='{$dataType}'";
					$data .= "data-url='{$toggleUrl}'";
				}

				return "<span style='{$style}' {$data} class='{$class}' title= '{$title}'> {$value} </span>";
			}
			else {
				return $attributeValue;
			}
		};
    }
}
