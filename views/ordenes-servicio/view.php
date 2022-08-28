<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenServicio */

$this->title = $model->id_orden_servicio;
\yii\web\YiiAsset::register($this);
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
                        echo Html::button(Html::a('Crear orden servicio', ['create']), ['class' => 'btn btn-success']) . ' ';
                    }

                    echo Html::button(Html::a('Regresar', ['/ordenes-servicio']), ['class' => 'btn btn-light']);
                ?>
                </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                   'tipoOrdenServicio.tipo_orden_servicio',
                   'cliente.cliente',
                   'estatus.estatus',
                   'unidadVehicular.unidad_vehicular',
                   'pozo.pozo',
                   'user.usuario_jefe_cuadrilla',
                   'user.usuario_cliente_solicitante',
                   'hora_salida',
                   'distancia_kms',
                   'combustible_aproximado_lts',
                   'ruta_descripcion',
                   'fecha',
                   'hora_entrada',
                   'origen_x',
                   'origen_y',
                   'destino_x',
                   'destino_y',
                   'fecha_hora_llegada_real',
                   'fecha_hora_salida_real',
                   'fecha_hora_inicio_trabajo',
                   'fecha_hora_final_trabajo',
                   'fecha_captura',
                   'user.usuario_captura',
                   'origen.origen_version',
                   [
                      'attribute' => 'fecha_version',
                      'value' => function($model) {
                          $fechaVersionDateTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha_version);
                          $formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                          $formatter->setPattern('dd \'de\' MMMM \'de\' YYYY');
                          return $formatter->format($fechaVersionDateTime);
                       }
                   ],
                   'usuarioVersion.username',
                ],
            ]) ?>
        </div>
    </div>
</div>
