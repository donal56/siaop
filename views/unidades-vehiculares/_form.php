<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadVehicular */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unidad-vehicular-form">
    <?php $form = ActiveForm::begin(['id' => 'unidad-vehicular-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?php  if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p>Unidad Vehicular <b>"<?=  $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?php  endif; ?>

                <div class="row my-3">
                       <?= $form->field($model, 'id_marca', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_tipo_unidad_vehicular', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'modelo', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'placa', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_clase_vehicular', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_tipo_combustible', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'vigencia_poliza', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'vigencia_trp', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'vigencia_trme', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'rendimiento_combustible', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'motor', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'tarjeta_circulacion', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'numero_identificacion_vehicular', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'poliza', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'permiso_ruta_sct', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'numero_economica', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'permiso_trp', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'permiso_trme', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                </div>
                   <div class= "row my-3">
                       <?= $form->field($model, 'activo', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                   </div>
                <br>
                <div class= "btn-page">
                    <?= Html::button('Guardar', ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("unidad-vehicular-form", false)'
                    ]) ?>
                    <?= Html::button('Guardar y crear otro', ['class' => 'btn btn-primary',
                        'onclick' => 'saveSimpleForm("unidad-vehicular-form", true)'
                    ]) ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
