<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadVehicular */

$this->title = $model->placa;
\yii\web\YiiAsset::register($this);
?>
<div class="unidad-vehicular-view">

    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <div class= "btn-page">
                <?php 
                    if(User::hasPermission('modificarUnidad Vehicular')) { 
                        echo Html::button(Html::a('Actualizar', ['update', 'id' => $model->id_unidad_vehicular]), ['class' => 'btn btn-primary']) . " ";
                    }
                    
                    if (User::hasPermission('eliminarUnidad Vehicular')) { 
                        echo Html::button(Html::a('Eliminar', ['delete', 'id' => $model->id_unidad_vehicular], [
                            'data' => [
                                'confirm' => '¿Esta seguro de eliminar este registro?',
                                'method' => 'post',
                            ],
                        ]), ['class' => 'btn btn-danger']) . " ";
                    } 

                    if(User::hasPermission('agregarUnidad Vehicular')) { 
                        echo Html::button(Html::a('Crear Unidad Vehicular', ['create']), ['class' => 'btn btn-success']) . ' ';
                    }

                    echo Html::button(Html::a('Regresar', ['/unidades-vehiculares']), ['class' => 'btn btn-light']);
                ?>
                </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                   'id_marca',
                   'id_tipo_unidad_vehicular',
                   'id_clase_vehicular',
                   'id_tipo_combustible',
                   'modelo',
                   'placa',
                   'motor',
                   'tarjeta_circulacion',
                   'numero_identificacion_vehicular',
                   'poliza',
                   'vigencia_poliza',
                   'permiso_ruta_sct',
                   'numero_economica',
                   'permiso_trp',
                   'vigencia_trp',
                   'permiso_trme',
                   'vigencia_trme',
                   'rendimiento_combustible',
                   [
                      'attribute' => 'activo',
                      'value' => fn($model) => $model->activo == 1 ? 'Sí' : 'No'
                   ],
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
