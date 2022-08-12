<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = 'Cambiando contraseña a usuario ' . $model->username;
?>

<div class="user-update">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="panel panel-default">
		<div class="panel-body">

			<div class="user-form">

				<?php $form = ActiveForm::begin([
					'id'=>'user',
					'layout'=>'horizontal',
				]); ?>

				<?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>

				<?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>


				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<?php if ( $model->isNewRecord ): ?>
							<?= Html::submitButton(
								'<span class="glyphicon glyphicon-plus-sign"></span> Crear',
								['class' => 'btn btn-success']
							) ?>
						<?php else: ?>
							<?= Html::submitButton(
								'<span class="glyphicon glyphicon-ok"></span> Guardar',
								['class' => 'btn btn-primary']
							) ?>
						<?php endif; ?>
                        <?= Html::a('Regresar', ['/user-management/user/index'], ['class' => 'btn btn-default']) ?>
					</div>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>

</div>
