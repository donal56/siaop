<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClaseVehicular */

$this->title = 'Crear clase vehicular';
?>
<div class="clase-vehicular-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
