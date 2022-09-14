<?php

namespace app\components;

class Constants {
    const HORIZONTAL_FIELD_OPTIONS = [
        'template' => "{label}\n<div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 col-form-label'],
        'options' => ['class' => 'row my-3 col-lg-6 col-md-12']
    ];
    
    const HORIZONTAL_FIELDGROUP_DISTANCE_OPTIONS = [
        'template' => "{label}\n<div class='col-sm-9'><div class= 'input-group'><span class= 'input-group-text'><i class= 'fa fa-ruler'></i></span>{input}<span class= 'input-group-text'>km(s)</span></div>\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 col-form-label'],
        'options' => ['class' => 'row my-3 col-lg-6 col-md-12']
    ];
    
    const HORIZONTAL_FIELDGROUP_GASOLINE_OPTIONS = [
        'template' => "{label}\n<div class='col-sm-9'><div class= 'input-group'><span class= 'input-group-text'><i class= 'fa fa-gas-pump'></i></span>{input}<span class= 'input-group-text'>lt(s)</span></div>\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 col-form-label'],
        'options' => ['class' => 'row my-3 col-lg-6 col-md-12']
    ];
    
    const HORIZONTAL_DATETIMEPICKER_OPTIONS = [
        'template' => "{label}\n<div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 col-form-label'],
        'options' => ['class' => 'row my-3 col-lg-6 col-md-12 datetimepicker']
    ];

    const HORIZONTAL_DATEPICKER_OPTIONS = [
        'template' => "{label}\n<div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 col-form-label'],
        'options' => ['class' => 'row my-3 col-lg-6 col-md-12 datepicker']
    ];

    const HORIZONTAL_TIMEPICKER_OPTIONS = [
        'template' => "{label}\n<div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
        'labelOptions' => ['class' => 'col-sm-3 col-form-label'],
        'options' => ['class' => 'row my-3 col-lg-6 col-md-12 timepicker']
    ];
}