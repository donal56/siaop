<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoUnidadVehicular */

$this->title = "Actualizar tipo unidad vehicular: " . $model->tipo_unidad_vehicular;
?>
<div class="tipo-unidad-vehicular-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
