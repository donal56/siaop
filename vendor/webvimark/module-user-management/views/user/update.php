<?php

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = 'Editado usuario ' . $model->username;
?>

<div class="user-update">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="panel panel-default">
		<div class="panel-body">

			<?php 
			if($model->use_fktipo==2) {
				echo $this->render('_form2', compact('model')); 
            }
            else {
				echo $this->render('_form', compact('model')); 
			}
			?>
		</div>
	</div>

</div>