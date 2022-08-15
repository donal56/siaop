<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FormatoSeccion */

$this->title = 'Actualizar formato seccion: $model->formato_seccion';
?>
<div class="formato-seccion-update">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
