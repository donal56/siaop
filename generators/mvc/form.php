<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

echo $form->field($generator, 'tableName')->textInput(['table_prefix' => $generator->getTablePrefix()]);
echo $form->field($generator, 'nameField');
echo $form->field($generator, 'modelClass');

$this->registerJs("
    $('#generator-tablename').change(function() {
        const tableName = $(this).val();
        const url = new URL(window.location.href);
        url.searchParams.set('tableName', tableName);
        window.location.href = url;
    });
");

