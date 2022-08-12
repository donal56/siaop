<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */

$this->title = "Cambiar mi correo";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-own-email">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="panel panel-default">
		<div class="panel-body">

			<div class="user-form">

				<?php $form = ActiveForm::begin([
					'id'=>'user',
					'layout'=>'horizontal',
					'validateOnBlur'=>false,
				]); ?>


				<?= $form->field($model, 'email')->input('email',['maxlength' => 255, 'autocomplete'=>'off']) ?>


				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<?= Html::submitButton(
							'<span class="glyphicon glyphicon-ok"></span> ' . "GUARDAR",
							['class' => 'btn btn-primary']
						) ?>
					</div>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>

</div>
