<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pozo */

$this->title = 'Crear pozo';
?>
<div class="pozo-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
