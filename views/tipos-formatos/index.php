<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoFormatoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos formatos';
?>
<div class="tipo-formato-index">
    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <div class= "btn-page">
                <?= Html::button(Html::a('Crear tipo formato' . '<span class="btn-icon-end"><i class="fa fa-plus"></i></span>', ['create']), ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'tipo_formato',
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
