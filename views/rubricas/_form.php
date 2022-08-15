<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rubrica */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rubrica-form">
    <?php $form = ActiveForm::begin(['id' => 'rubrica-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?php  if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p>Rubrica <b>"<?=  $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?php  endif; ?>

                <div class="row my-3">
                       <?= $form->field($model, 'id_formato_seccion', ['options' => ['class' => 'form-group col-sm-4']])
                              ->dropDownList(\app\models\FormatoSeccion::generateDropdownData(), ['prompt' => '--Seleccione uno--', 'class' => 'form-control']) ?>

                       <?= $form->field($model, 'orden', ['options' => ['class' => 'form-group col-sm-4']])->textInput(['class' => 'form-control']) ?>

                       <?= $form->field($model, 'rubrica', ['options' => ['class' => 'form-group col-sm-4']])
                              ->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                </div>
                <br>
                <div class= "btn-page">
                    <?= Html::button('Guardar', ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("rubrica-form", false)'
                    ]) ?>
                    <?= Html::button('Guardar y crear otro', ['class' => 'btn btn-primary',
                        'onclick' => 'saveSimpleForm("rubrica-form", true)'
                    ]) ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
