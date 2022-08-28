<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenServicio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orden-servicio-form">
    <?php $form = ActiveForm::begin(['id' => 'orden-servicio-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?php  if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p>Orden Servicio <b>"<?=  $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?php  endif; ?>

                <div class="row my-3">
                       <?= $form->field($model, 'id_tipo_orden_servicio', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_cliente', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_estatus', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'ruta_descripcion', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'fecha', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'hora_entrada', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'origen_x', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'origen_y', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'destino_x', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'destino_y', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'fecha_captura', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'usuario_captura', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'origen_version', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_unidad_vehicular', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'id_pozo', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'usuario_jefe_cuadrilla', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'usuario_cliente_solicitante', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'distancia_kms', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'hora_salida', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'fecha_hora_llegada_real', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'fecha_hora_salida_real', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'fecha_hora_inicio_trabajo', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'fecha_hora_final_trabajo', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                       <?= $form->field($model, 'combustible_aproximado_lts', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                </div>
                <br>
                <div class= "btn-page">
                    <?= Html::button('Guardar', ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("orden-servicio-form", false)'
                    ]) ?>
                    <?php 
                        if(User::hasPermission('agregarOrdenServicio')) { 
                            Html::button('Guardar y crear otro', ['class' => 'btn btn-primary', 'onclick' => 'saveSimpleForm("orden-servicio-form", true)']) . ' ';
                        }
                    ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
