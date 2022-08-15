<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Actividad */

$this->title = 'Crear actividad';
?>
<div class="actividad-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
