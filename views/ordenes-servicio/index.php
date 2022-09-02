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
                        echo Html::button(Html::a('Crear orden servicio terrestre' . '<span class="btn-icon-end"><i class="fa fa-plus"></i></span>', ['create', 'tipo' => 1]), ['class' => 'btn btn-success']);
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
                    'id_orden_servicio',
                    [
                        'attribute' => 'id_cliente',
                        'value' => fn($model) => $model->cliente->cliente,
                        'filter' => app\models\Cliente::generateDropdownData()
                    ],
                    [
                        'attribute' => 'id_pozo',
                        'value' => fn($model) => $model->pozo->pozo,
                        'filter' => app\models\Pozo::generateDropdownData()                   
                    ],
                    [
                        'attribute' => 'fecha',
                        'value' => function($model) {
                            $fechaDateTime = DateTime::createFromFormat('Y-m-d H:i:s.u', $model->fecha);
                            $formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                            $formatter->setPattern('dd \'de\' MMMM \'de\' YYYY');
                            return $formatter->format($fechaDateTime);
                         }
                    ],
                    'hora_entrada',
                    'usuarioJefeCuadrilla.nombreCompleto',
                    [
                        'attribute' => 'id_tipo_orden_servicio',
                        'value' => fn($model) => $model->tipoOrdenServicio->tipo_orden_servicio,
                        'filter' => app\models\TipoOrdenServicio::generateDropdownData()                   
                    ],
                    [
                        'attribute' => 'id_estatus',
                        'value' => fn($model) => $model->estatus->estatus,
                        'filter' => app\models\Estatus::generateDropdownData()                   
                    ],
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
