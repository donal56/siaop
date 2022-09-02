<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Marca */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marca-form">
    <?php $form = ActiveForm::begin(['id' => 'marca-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?php  if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p>Marca <b>"<?=  $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?php  endif; ?>

                <div class="row my-3">
                       <?= $form->field($model, 'marca', ['options' => ['class' => 'form-group col-sm-4']])
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
                        'onclick' => 'saveSimpleForm("marca-form", false)'
                    ]) ?>
                    <?php 
                        if(User::hasPermission('agregarMarca')) { 
                            echo Html::button('Guardar y crear otro', ['class' => 'btn btn-primary', 'onclick' => 'saveSimpleForm("marca-form", true)']) . ' ';
                        }
                    ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
