<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoArchivo */

$this->title = 'Crear tipo archivo';
?>
<div class="tipo-archivo-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
