<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empresa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="empresa-form">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <?= $form->field($model, 'emp_codigo', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => 20]) ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'emp_nombre', ['options' => ['class' => 'form-group col-sm-8']])->textInput(['maxlength' => 120]) ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'emp_descripcion', ['options' => ['class' => 'form-group col-sm-8']])->textArea(['maxlength' => 255]) ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'emp_activo', ['options' => ['class' => 'form-group col-sm-8']])->checkbox() ?>
                </div>
                <div class="row">
                    <div class="form-group col-sm-8">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                        <?= Html::a("Cancelar", ['index'], ['class' => 'btn btn-danger']);?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>