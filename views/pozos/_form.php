<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pozo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pozo-form">
    <?php $form = ActiveForm::begin(['id' => 'pozo-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?php  if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p>Pozo <b>"<?=  $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?php  endif; ?>

                <div class="row my-3">
                       <?= $form->field($model, 'pozo', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                       <?= $form->field($model, 'ubicacion_descripcion', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                       <?= $form->field($model, 'ubicacion_x', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                       <?= $form->field($model, 'ubicacion_y', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                </div>
                   <div class= "row my-3">
                       <?= $form->field($model, 'activo', ['options' => ['class' => 'form-group col-sm-4 form-check custom-checkbox checkbox-info']])->checkbox(['class' => 'form-check-input', 'labelOptions' => ['style' => 'line-height: 23px']]) ?>

                   </div>
                <br>
                <div class= "btn-page">
                    <?= Html::button('Guardar', ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("pozo-form", false)'
                    ]) ?>
                    <?= Html::button('Guardar y crear otro', ['class' => 'btn btn-primary',
                        'onclick' => 'saveSimpleForm("pozo-form", true)'
                    ]) ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
