<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCombustible */

$this->title = "Actualizar tipo combustible: " . $model->tipo_combustible;
?>
<div class="tipo-combustible-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
