<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;use webvimark\modules\UserManagement\models\User;


/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
?>
<div class="cliente-index">
    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <?php 
                if(User::hasPermission('agregarCliente')) { 
                    echo '<div class= "btn-page">';
                        echo Html::button(Html::a('Crear cliente' . '<span class="btn-icon-end"><i class="fa fa-plus"></i></span>', ['create']), ['class' => 'btn btn-success']);
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
                        'attribute' => 'razon_social',
                        'format' => 'text',
                        'filterInputOptions' => [
                             'class' => 'form-control',
                             'placeholder' => '🔎︎',
                       ],
                   ],
                    [
                        'attribute' => 'rfc',
                        'format' => 'text',
                        'filterInputOptions' => [
                             'class' => 'form-control',
                             'placeholder' => '🔎︎',
                       ],
                   ],
                    [
                        'attribute' => 'activo',
                        'format' => fn($val) => $val == 1 ? "Sí" : "No",
                        'filterInputOptions' => [
                             'class' => 'form-control',
                             'placeholder' => '🔎︎',
                       ],
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
