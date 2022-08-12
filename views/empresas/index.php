<?php

use webvimark\modules\UserManagement\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmpresaSerach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Empresas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empresa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php # Html::a('Agregar empresa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="panel panel-default">
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'emp_codigo',
                    'emp_nombre',
                    'emp_descripcion:text',
                    'emp_activo:boolean',
                    [
                        'label' => '',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::a('<i class= "fa fa-building"></i>  Administrar<br>centros (' . sizeof($model->centros) . ")", ['/centros', 'eid' => $model->emp_clave], ['class' => 'btn btn-primary btn-sm', 'data-pjax' => 0]);
                        }
                    ],
                    [
                        'label' => '',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::a('<i class= "fa fa-cog"></i> Configurar<br>parámetros', ['parametros', 'id' => $model->emp_clave], ['class' => 'btn btn-default btn-sm', 'data-pjax' => 0]);
                        }
                    ],
                    [
                        'class'  => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'visible' => User::canRoute('empresas/create')
                    ],
                ],
                'tableOptions' => [
                    'class' => 'table table-striped table-hover table-responsive'
                ]
            ]); ?>
        </div>
    </div>
</div>
