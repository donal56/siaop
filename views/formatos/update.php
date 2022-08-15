<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Formato */

$this->title = 'Actualizar formato: $model->titulo';
?>
<div class="formato-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
