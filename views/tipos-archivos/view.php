<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\TipoArchivo */

$this->title = $model->tipo_archivo;
\yii\web\YiiAsset::register($this);
?>
<div class="tipo-archivo-view">

    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <div class= "btn-page">
                <?php 
                    if(User::hasPermission('modificarTipo Archivo')) { 
                        echo Html::button(Html::a('Actualizar', ['update', 'id' => $model->id_tipo_archivo]), ['class' => 'btn btn-primary']) . " ";
                    }
                    
                    if (User::hasPermission('eliminarTipo Archivo')) { 
                        echo Html::button(Html::a('Eliminar', ['delete', 'id' => $model->id_tipo_archivo], [
                            'data' => [
                                'confirm' => '¿Esta seguro de eliminar este registro?',
                                'method' => 'post',
                            ],
                        ]), ['class' => 'btn btn-danger']) . " ";
                    } 

                    if(User::hasPermission('agregarTipo Archivo')) { 
                        echo Html::button(Html::a('Crear Tipo Archivo', ['create']), ['class' => 'btn btn-success']) . ' ';
                    }

                    echo Html::button(Html::a('Regresar', ['/tipos-archivos']), ['class' => 'btn btn-light']);
                ?>
                </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                   'tipo_archivo',
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