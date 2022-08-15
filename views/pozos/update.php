<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pozo */

$this->title = 'Actualizar pozo: $model->pozo';
?>
<div class="pozo-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
