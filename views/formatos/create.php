<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Formato */

$this->title = 'Crear formato';
?>
<div class="formato-create">
    <?= $this->render('_form', [
        'model' => $model,
        'guardado' => $guardado
    ]) ?>
</div>
