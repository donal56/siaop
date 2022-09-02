<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenServicio */

$this->title = 'Crear orden de servicio ' . $model->tipoOrdenServicio->tipo_orden_servicio;
?>
<div class="orden-servicio-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
