<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenServicio */

$this->title = "Actualizar orden de servicio: " . $model->id_orden_servicio;
?>
<div class="orden-servicio-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
