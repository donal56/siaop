<?php

use app\assets\GoogleMapsAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Pozo */

$this->title = $model->pozo;
YiiAsset::register($this);
GoogleMapsAsset::register($this);

?>
<div class="pozo-view">

    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= Html::encode($this->title) ?></h4>
            <br>
            <div class= "btn-page">
                <?php 
                    if(User::hasPermission('modificarPozo')) { 
                        echo Html::button(Html::a('Actualizar', ['update', 'id' => $model->id_pozo]), ['class' => 'btn btn-primary']) . " ";
                    }
                    
                    if (User::hasPermission('eliminarPozo')) { 
                        echo Html::button(Html::a('Eliminar', ['delete', 'id' => $model->id_pozo], [
                            'data' => [
                                'confirm' => '¿Esta seguro de eliminar este registro?',
                                'method' => 'post',
                            ],
                        ]), ['class' => 'btn btn-danger']) . " ";
                    } 

                    if(User::hasPermission('agregarPozo')) { 
                        echo Html::button(Html::a('Crear pozo', ['create']), ['class' => 'btn btn-success']) . ' ';
                    }

                    echo Html::button(Html::a('Regresar', ['/pozos']), ['class' => 'btn btn-light']);
                ?>
                </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                   'pozo',
                   'ubicacion_descripcion',
                   'ubicacion_x',
                   'ubicacion_y',
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
    <div class="card">
        <div class="card-body">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="gooMap"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$js = <<<JS
    const gmap = new GMap();
    gmap.setCenter({$model->ubicacion_x}, {$model->ubicacion_y}, 18);
    gmap.addMarker({$model->ubicacion_x}, {$model->ubicacion_y}, '{$model->pozo}', '', true);
JS;

$this->registerJs($js, $this::POS_END, 'gooMap');
