<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoArchivo */

$this->title = 'Actualizar tipo archivo: $model->tipo_archivo';
?>
<div class="tipo-archivo-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
