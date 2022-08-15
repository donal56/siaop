<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClaseVehicular */

$this->title = 'Actualizar clase vehicular: $model->clase_vehicular';
?>
<div class="clase-vehicular-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
