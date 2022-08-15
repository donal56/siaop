<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoFormato */

$this->title = 'Actualizar tipo formato: $model->tipo_formato';
?>
<div class="tipo-formato-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
