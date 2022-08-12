<?php

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = 'Creación de usuarios';
?>

<div class="user-create">
	<h2 class="lte-hide-title"><?= $this->title ?></h2>
	<div class="panel panel-default">
		<div class="panel-body">
			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>
</div>