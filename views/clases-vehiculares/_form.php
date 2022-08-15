<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClaseVehicular */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clase-vehicular-form">
    <?php $form = ActiveForm::begin(['id' => 'clase-vehicular-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?php  if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p>Clase Vehicular <b>"<?=  $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?php  endif; ?>

                <div class="row my-3">
                       <?= $form->field($model, 'clase_vehicular', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                       <?= $form->field($model, 'descripcion', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                </div>
                   <div class= "row my-3">
                       <?= $form->field($model, 'activo', ['options' => ['class' => 'form-group col-sm-4 form-check custom-checkbox checkbox-info']])->checkbox(['class' => 'form-check-input', 'labelOptions' => ['style' => 'line-height: 23px']]) ?>

                   </div>
                <br>
                <div class= "btn-page">
                    <?= Html::button('Guardar', ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("clase-vehicular-form", false)'
                    ]) ?>
                    <?= Html::button('Guardar y crear otro', ['class' => 'btn btn-primary',
                        'onclick' => 'saveSimpleForm("clase-vehicular-form", true)'
                    ]) ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
