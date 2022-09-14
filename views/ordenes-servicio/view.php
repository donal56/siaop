<?php

use app\assets\GoogleMapsAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\YiiAsset;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenServicio */

$this->title = "Orden de servicio #" . $model->id_orden_servicio;

YiiAsset::register($this);
GoogleMapsAsset::register($this);

?>
<div class="orden-servicio-view">

    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <div class= "btn-page">
                <?php 
                    if(User::hasPermission('modificarOrdenServicio')) { 
                        echo Html::button(Html::a('Actualizar', ['update', 'id' => $model->id_orden_servicio]), ['class' => 'btn btn-primary']) . " ";
                    }
                    
                    if (User::hasPermission('eliminarOrdenServicio')) { 
                        echo Html::button(Html::a('Eliminar', ['delete', 'id' => $model->id_orden_servicio], [
                            'data' => [
                                'confirm' => '¿Esta seguro de eliminar este registro?',
                                'method' => 'post',
                            ],
                        ]), ['class' => 'btn btn-danger']) . " ";
                    } 

                    if(User::hasPermission('agregarOrdenServicio')) { 
                        echo Html::button(Html::a('Crear orden terrestre', ['create', 'tipo' => 1]), ['class' => 'btn btn-success']) . ' ';
                    }

                    echo Html::button(Html::a('Regresar', ['/ordenes-servicio']), ['class' => 'btn btn-light']);
                ?>
                </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'fecha',
                        'value' => function($model) {
                            $fechaDateTime = DateTime::createFromFormat('Y-m-d', $model->fecha);
                            $formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                            $formatter->setPattern('dd \'de\' MMMM \'de\' YYYY');
                            return $formatter->format($fechaDateTime);
                        }
                    ],
                    'hora_entrada',
                    'tipoOrdenServicio.tipo_orden_servicio',
                    [
                        'attribute' => 'cliente.razon_social',
                        'label' => 'Cliente'
                    ],
                    'unidadVehicular.placa',
                    'pozo.pozo',
                    'hora_salida',
                    'distancia_kms',
                    'combustible_aproximado_lts',
                    'estatus.estatus',
                    'ruta_descripcion',
                    [
                        'label' => 'Tiempo registrado',
                        'value' => function($model) {
                            $formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                            $formatter->setPattern('dd \'de\' MMMM \'de\' YYYY H:i');
                            
                            $fechaDateLlegadaTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha_hora_llegada_real);
                            $fechaDateSalidaTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha_hora_salida_real);

                            return $formatter->format($fechaDateLlegadaTime) . ' - ' . $formatter->format($fechaDateSalidaTime);
                        }
                    ],
                    [
                        'label' => 'Tiempo de trabajo',
                        'value' => function($model) {
                            $formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                            $formatter->setPattern('dd \'de\' MMMM \'de\' YYYY H:i');
                            
                            $fechaDateInicioTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha_hora_inicio_trabajo);
                            $fechaDateFInalTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha_hora_final_trabajo);

                            return $formatter->format($fechaDateInicioTime) . ' - ' . $formatter->format($fechaDateFInalTime);
                        }
                    ],
                    [
                        'attribute' => 'usuarioJefeCuadrilla.username',
                        'label' => 'Jefe de cuadrilla'
                    ],
                    [
                        'attribute' => 'usuarioClienteSolicitante.username',
                        'label' => 'Cliente solicitante'
                    ],
                    [
                      'label' => 'Captura',
                      'value' => function($model) {
                          $fechaDateTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha_captura);
                          $formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                          $formatter->setPattern('dd \'de\' MMMM \'de\' YYYY');
                          return $formatter->format($fechaDateTime) . ", por " . $model->usuarioCaptura->username;
                       }
                   ],
                ],
            ]) ?>

            <br>

            <div id= "gooMap" style= "height: 350px"></div>

            <!-- TODO: Lista de actividades -->
        </div>
    </div>
</div>

<?php
    $js = <<<JS
        const gmap = new GMap();
        gmap.setCenter({$model->origen_x}, {$model->origen_y}, 18);
        gmap.createMarker({$model->origen_x}, {$model->origen_y}, {title: "Origen"});
        gmap.createMarker({$model->destino_x}, {$model->destino_y}, {title: "Destino"});
JS;
    $this->registerJs($js, $this::POS_END);
