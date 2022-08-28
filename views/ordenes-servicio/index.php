<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;use webvimark\modules\UserManagement\models\User;


/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdenServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ordenes servicios';
?>
<div class="orden-servicio-index">
    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <?php 
                if(User::hasPermission('agregarOrdenServicio')) { 
                    echo '<div class= "btn-page">';
                        echo Html::button(Html::a('Crear orden servicio' . '<span class="btn-icon-end"><i class="fa fa-plus"></i></span>', ['create']), ['class' => 'btn btn-success']);
                    echo '</div>';
                }
            ?>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'id_tipo_orden_servicio',
                        'value' => fn($model) => $model->tipoOrdenServicio->tipo_orden_servicio,
                        'filter' => app\models\TipoOrdenServicio::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_cliente',
                        'value' => fn($model) => $model->cliente->cliente,
                        'filter' => app\models\Cliente::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_estatus',
                        'value' => fn($model) => $model->estatus->estatus,
                        'filter' => app\models\Estatus::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_unidad_vehicular',
                        'value' => fn($model) => $model->unidadVehicular->unidad_vehicular,
                        'filter' => app\models\UnidadVehicular::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_pozo',
                        'value' => fn($model) => $model->pozo->pozo,
                        'filter' => app\models\Pozo::generateDropdownData()                   ],
                    //'usuario_jefe_cuadrilla',
                    //'usuario_cliente_solicitante',
                    //'hora_salida',
                    //'distancia_kms',
                    //'combustible_aproximado_lts',
                    //'ruta_descripcion',
                    //'fecha',
                    //'hora_entrada',
                    //'origen_x',
                    //'origen_y',
                    //'destino_x',
                    //'destino_y',
                    //'fecha_hora_llegada_real',
                    //'fecha_hora_salida_real',
                    //'fecha_hora_inicio_trabajo',
                    //'fecha_hora_final_trabajo',
                    //'fecha_captura',
                    //'usuario_captura',
                    //'origen_version',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
                'tableOptions' => [
                    'class' => 'table table-striped table-hover table-responsive-sm'
                ]
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
