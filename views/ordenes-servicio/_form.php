<?php

use app\assets\GoogleMapsAsset;
use app\components\Constants;
use app\models\UnidadVehicular;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\ArrayHelper;

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
                        <p>Orden de servicio #<b>"<?=  $guardado ?>"</b> guardada exitosamente.</p>
                    </div>
                <?php  endif; ?>
                
                <div class="row my-3">
                    <?= $form->field($model, 'id_cliente', Constants::HORIZONTAL_FIELD_OPTIONS)
                                ->dropDownList(\app\models\Cliente::generateDropdownData(), [
                                    'prompt' => '--Seleccione uno--', 
                                    'class' => 'form-control'
                                ]) ?>

                    <?php 
                        $btnMapaDestino = '<button class="btn btn-primary btn-xs" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDestino" aria-expanded="false" aria-controls="collapseDestino">
                        Ubicar&nbsp;&nbsp;<i class= "fa fa-map-location-dot"></i></button>';

                        echo $form->field($model, 'id_pozo', Constants::HORIZONTAL_FIELD_OPTIONS)
                                ->dropDownList(\app\models\Pozo::generateDropdownData(), [
                                    'prompt' => '--Seleccione uno--', 
                                    'class' => 'form-control'
                                ])
                                ->label('Destino ' . $btnMapaDestino) 
                    ?>
                </div>

                <div class="row my-3">
                    <?= $form->field($model, 'fecha', Constants::HORIZONTAL_DATEPICKER_OPTIONS) ?>
                    <?= $form->field($model, 'hora_entrada', Constants::HORIZONTAL_TIMEPICKER_OPTIONS) ?>
                </div>

                
                <div id= "collapseDestino" class= "row my-3 mx-auto collapse">
                    <h4>Destino del servicio </h4>
                    <?= Html::hiddenInput("OrdenServicio[destino]", $model->destino, [
                        "id"        =>  "ordenservicio-destino",
                        "required"  =>  true
                    ]) ?>
                </div>

                <h3>Servicios</h3>

                <div class= "row my-3">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_inner',
                        'widgetBody' => '.container-os',
                        'widgetItem' => '.os-item',
                        'min' => 1,
                        'insertButton' => '.add-os',
                        'deleteButton' => '.remove-os',
                        'model' => $serviciosActividades[0],
                        'formId' => 'orden-servicio-form',
                        'formFields' => [
                            'Actividad',
                            'Cantidad'
                        ]
                    ]); ?>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Cantidad</th>
                                <th>
                                    <button type="button" class="add-os btn btn-success btn-sm">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="container-os">
                            <?php foreach ($serviciosActividades as $ix => $servicioActividad) : ?>
                            <tr class="os-item">
                                <?php 
                                    if (!$servicioActividad->isNewRecord) {
                                        echo Html::activeHiddenInput($servicioActividad, "[{$ix}]id_orden_servicio_actividad");
                                    }
                                ?>
                                <td>
                                    <?php
                                        echo $form
                                            ->field($servicioActividad, "[{$ix}]id_actividad")
                                            ->label(false)
                                            ->dropDownList(
                                                ArrayHelper::map($actividades, 'id_actividad', 
                                                fn($model) => $model->actividad . " (" . $model->unidadMedida->unidad_medida . ")", 
                                            ), [
                                                'data-model' => $servicioActividad->id_actividad,
                                                'prompt' => 'Seleccione uno'
                                            ]);
                                    ?>
                                </td>
                                <td>
                                    <?= $form->field($servicioActividad, "[{$ix}]cantidad")
                                            ->input('number', ['min' => 1, 'step' => 1])
                                            ->label(false) ?>
                                </td>
                                <td style="width: 90px;">
                                    <button type="button" class="remove-os btn btn-danger btn-sm">
                                        <span class="fa fa-minus"></span>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php DynamicFormWidget::end(); ?>
                </div>

                <div class="row my-3 mx-auto">
                    <?= Html::hiddenInput("OrdenServicio[origen]", $model->origen, [
                        "id"        =>  "ordenservicio-origen",
                        "required"  =>  true
                    ]) ?>
                </div>

                <div class="row my-3">
                    <?= $form->field($model, 'distancia_kms', Constants::HORIZONTAL_FIELDGROUP_DISTANCE_OPTIONS)
                        ->textInput(['readonly' => true]) ?>
                    <?= $form->field($model, 'combustible_aproximado_lts', Constants::HORIZONTAL_FIELDGROUP_GASOLINE_OPTIONS)
                        ->textInput(['readonly' => true]) ?>
                </div>

                <div class="row my-3">
                    <?= $form->field($model, 'usuario_jefe_cuadrilla', Constants::HORIZONTAL_FIELD_OPTIONS)
                                ->dropDownList(User::generateDropdownData(), [
                                    'prompt' => '--Seleccione uno--', 
                                    'class' => 'form-control'
                                ]) ?>
                    <?= $form->field($model, 'id_unidad_vehicular', Constants::HORIZONTAL_FIELD_OPTIONS)
                                ->dropDownList(UnidadVehicular::generateDropdownData(), [
                                    'prompt' => '--Seleccione uno--', 
                                    'class' => 'form-control'
                                ]) ?>
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

$insert = $model->isNewRecord ? 1 : 0;

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
    const unidadVehicularSelect = document.querySelector("#ordenservicio-id_unidad_vehicular");
    const origenField = document.getElementById("ordenservicio-origen");
    const destinoField = document.getElementById("ordenservicio-destino");
    const combustibleField = document.getElementById("ordenservicio-combustible_aproximado_lts");
    const distanciaField = document.getElementById("ordenservicio-distancia_kms");
    let lastPozoSelected = null;

    const gmapOrigen = GMap.createPinpointMap('ordenservicio-origen', '$x1', '$y1', {
        mapStyle : 'height: 300px; width: 90%',
        mapType : 'roadmap',
        zoom : 16,
        _callback : function(marker) {
            debounce(calcularRecorrido, 500)();
        }
    });

    const gmapDestino = GMap.createPinpointMap('ordenservicio-destino', '$x2', '$y2', {
        mapStyle : 'height: 300px; width: 90%',
        mapType : 'roadmap',
        zoom : 16,
        _callback : function(marker) {
            
            const pozoSeleccionado = lastPozoSelected !== null &&
                                        marker.position.lat() == lastPozoSelected.lat() &&
                                        marker.position.lng() == lastPozoSelected.lng();

            if(!pozoSeleccionado) {
                pozoSelect.value = "";
            }

            debounce(calcularRecorrido, 500)();
        }
    });

    unidadVehicularSelect.onchange = calcularCombustible;

    pozoSelect.onchange = function(event) {
        const id = event.target.value;
        if(!id) return;

        get("/pozos/json/" + id, null, function(model) {
            const ubicacion = new google.maps.LatLng(model.ubicacion_x, model.ubicacion_y);
            lastPozoSelected = ubicacion;

            google.maps.event.trigger(gmapOrigen.innerMap, 'click', { latLng : ubicacion });
        });
    };

    if($insert) {
        calcularRecorrido();
        calcularCombustible();
    }

    function calcularRecorrido() {
        const origen = origenField.value;
        const destino = destinoField.value;

        if(!origen || !destino) return;

        if(origen == destino) {
            combustibleField.value = 0;
            distanciaField.value = 0;
            return;
        }
        
        const origenParts = origen.split(",");
        const destinoParts = destino.split(",");

        if(origenParts.length != 2 || destinoParts.length != 2) return;

        const coordOrigen = new google.maps.LatLng(origenParts[0], origenParts[1]);
        const coordDestino = new google.maps.LatLng(destinoParts[0], destinoParts[1]);

        gmapOrigen.removeRoutes();
        gmapOrigen.getDirections(coordOrigen, coordDestino, [], "DRIVING", new Date(), function(status, result) {
            if(status) {
                const legs = result.routes[0].legs;
                const distance = legs.reduce((acc, el) => acc + (el.distance.value / 1000), 0);
                distanciaField.value = distance.toFixed(2);
            }
            else {
                distanciaField.value = -1;
            }
            calcularCombustible();
        }, false, {provideRouteAlternatives : false});
    }

    function calcularCombustible() {

        if(unidadVehicularSelect.value && distanciaField.value) {
            get("/unidades-vehiculares/json/" + unidadVehicularSelect.value, null, function(model) {
                const hint = document.querySelector("div.field-ordenservicio-combustible_aproximado_lts div.help-block");

                if(model.rendimiento_combustible) {
                    combustibleField.value = (distanciaField.value * model.rendimiento_combustible).toFixed(2);
                    hint.textContent = null;
                }
                else {
                    combustibleField.value = -1;
                    hint.textContent = "Configure el rendimiento del vehículo para el cálculo correcto del uso de combustible";
                }
            });
        }
        else {
            unidadVehicularSelect.value = 0;
        }
    }

JS;

$this->registerJs($js);