<?php

use app\assets\GoogleMapsAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenServicio */
/* @var $form yii\widgets\ActiveForm */

GoogleMapsAsset::register($this);

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
                    <?= $form->field($model, 'id_cliente', ['options' => ['class' => 'form-group col-sm-4']])
                              ->dropDownList(\app\models\Cliente::generateDropdownData(), ['prompt' => '--Seleccione uno--', 'class' => 'form-control']) ?>

                    <?= $form->field($model, 'id_pozo', ['options' => ['class' => 'form-group col-sm-4']])
                              ->dropDownList(\app\models\Pozo::generateDropdownData(), ['prompt' => '--Seleccione uno--', 'class' => 'form-control'])
                              ->label('Destino') ?>
                </div

                <h2>Seleccione un pozo de la lista o ubique manualmente: </h2>

                <div class="row my-3">
                    <?= Html::hiddenInput("OrdenServicio[origen]", $model->origen, [
                        "id"        =>  "ordenservicio-origen",
                        "required"  =>  true
                    ]) ?>
                </div>

                <div class="row my-3">
                    <?= Html::hiddenInput("OrdenServicio[destino]", $model->destino, [
                        "id"        =>  "ordenservicio-destino",
                        "required"  =>  true
                    ]) ?>
                </div>

                <div class="row my-3">
                    <?= $form->field($model, 'fecha', ['options' => ['class' => 'form-group col-sm-4']]) ?>
                    
                    <?= $form->field($model, 'hora_entrada', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                    <?= $form->field($model, 'usuario_jefe_cuadrilla', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                    <?= $form->field($model, 'id_unidad_vehicular', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                    <?= $form->field($model, 'distancia_kms', ['options' => ['class' => 'form-group col-sm-4']]) ?>
                    <?= $form->field($model, 'combustible_aproximado_lts', ['options' => ['class' => 'form-group col-sm-4']]) ?>

                </div>
                <br>
                <div class= "btn-page">
                    <?= Html::button('Guardar', ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("orden-servicio-form", false)'
                    ]) ?>
                    <?php 
                        if(User::hasPermission('agregarOrdenServicio')) { 
                            echo Html::button('Guardar y crear otro', ['class' => 'btn btn-primary', 'onclick' => 'saveSimpleForm("orden-servicio-form", true)']) . ' ';
                        }
                    ?>
                    <?= Html::button(Html::a('Regresar', ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php

$x1 = '17.992175832504604';
$y1 = '-92.94522720755232';
$x2 = $x1;
$y2 = $y1;

if(isset($model->origen_x)) {
    $x1 = $model->origen_x;
    $y1 = $model->origen_y;
}

if(isset($model->destino_x)) {
    $x2 = $model->destino_x;
    $y2 = $model->destino_y;
}

$js = <<<JS
    const pozoSelect = document.querySelector("#ordenservicio-id_pozo");

    const gmapOrigen = GMap.createPinpointMap('ordenservicio-origen', '$x1', '$y1', {
        mapStyle : 'height: 300px; width: 1000px',
        mapType : 'roadmap',
        zoom : 16,
        _callback : function(marker) {
            pozoSelect.value = "";
        }
    });

    const gmapDestino = GMap.createPinpointMap('ordenservicio-destino', '$x2', '$y2', {
        mapStyle : 'height: 300px; width: 1000px',
        mapType : 'roadmap',
        zoom : 16
    });

    pozoSelect.onchange = function(event) {
        const id = event.target.value;
        if(!id) return;

        gmapOrigen.addOverlayMessage("Cargando...");

        get("/pozos/json/" + id, null, function(model) {
            gmapOrigen.removeOverlayMessage();
            const ubicacion = new google.maps.LatLng(model.ubicacion_x, model.ubicacion_y);
            google.maps.event.trigger(gmapOrigen.innerMap, 'click', { latLng : ubicacion });
        });
    };
JS;

$this->registerJs($js);