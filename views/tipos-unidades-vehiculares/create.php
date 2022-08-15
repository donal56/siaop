<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoUnidadVehicular */

$this->title = 'Crear tipo unidad vehicular';
?>
<div class="tipo-unidad-vehicular-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
