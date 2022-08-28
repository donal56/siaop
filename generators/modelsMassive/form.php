<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

/**
 * TODO:
 *  - Considerar el campo descriptivo (name_field)
 */

echo $form->field($generator, 'tables')->checkboxList($generator->getDbTables(), [
    'unselect' => null,
    'separator' => '<br>'
]);
