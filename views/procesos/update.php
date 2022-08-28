<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Proceso */

$this->title = "Actualizar proceso: " . $model->proceso;
?>
<div class="proceso-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
