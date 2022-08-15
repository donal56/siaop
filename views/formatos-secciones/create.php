<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FormatoSeccion */

$this->title = 'Crear formato seccion';
?>
<div class="formato-seccion-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
