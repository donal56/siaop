<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadVehicular */

$this->title = "Actualizar unidad vehicular: " . $model->placa;
?>
<div class="unidad-vehicular-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
