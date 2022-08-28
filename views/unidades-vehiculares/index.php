<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;use webvimark\modules\UserManagement\models\User;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UnidadVehicularSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unidades vehiculares';
?>
<div class="unidad-vehicular-index">
    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <?php 
                if(User::hasPermission('agregarUnidadVehicular')) { 
                    echo '<div class= "btn-page">';
                        echo Html::button(Html::a('Crear unidad vehicular' . '<span class="btn-icon-end"><i class="fa fa-plus"></i></span>', ['create']), ['class' => 'btn btn-success']);
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
                        'attribute' => 'id_marca',
                        'value' => fn($model) => $model->marca->marca,
                        'filter' => app\models\Marca::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_tipo_unidad_vehicular',
                        'value' => fn($model) => $model->tipoUnidadVehicular->tipo_unidad_vehicular,
                        'filter' => app\models\TipoUnidadVehicular::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_clase_vehicular',
                        'value' => fn($model) => $model->claseVehicular->clase_vehicular,
                        'filter' => app\models\ClaseVehicular::generateDropdownData()                   ],
                    [
                        'attribute' => 'id_tipo_combustible',
                        'value' => fn($model) => $model->tipoCombustible->tipo_combustible,
                        'filter' => app\models\TipoCombustible::generateDropdownData()                   ],
                    [
                        'attribute' => 'modelo',
                        'format' => 'text',
                        'filterInputOptions' => [
                             'class' => 'form-control',
                             'placeholder' => '🔎︎',
                       ],
                   ],
                    //'placa',
                    //'motor',
                    //'tarjeta_circulacion',
                    //'numero_identificacion_vehicular',
                    //'poliza',
                    //'vigencia_poliza',
                    //'permiso_ruta_sct',
                    //'numero_economica',
                    //'permiso_trp',
                    //'vigencia_trp',
                    //'permiso_trme',
                    //'vigencia_trme',
                    //'rendimiento_combustible',
                    //'activo',
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
