<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadMedida */

$this->title = 'Crear unidad medida';
?>
<div class="unidad-medida-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
