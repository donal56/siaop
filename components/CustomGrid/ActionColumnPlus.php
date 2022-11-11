<?php

namespace app\components\customGrid;

use yii\grid\ActionColumn;

class ActionColumnPlus extends ActionColumn {
	
     public $filter = "";

     protected function renderFilterCellContent() {
        return $this->filter;
     }
}
