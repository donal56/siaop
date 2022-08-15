<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Grupo de permisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-group-view">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="panel panel-default">
		<div class="panel-body">

			<p>
				<?= Html::a('Editar', ['update', 'id' => $model->code], ['class' => 'btn btn-sm btn-primary']) ?>
				<?= Html::a('Crear', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
				<?= Html::a('Borrar', ['delete', 'id' => $model->code], [
					'class' => 'btn btn-sm btn-danger pull-right',
					'data' => [
						'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					],
				]) ?>
			</p>

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'name:text:Nombre',
					'code:text:Código',
					'created_at:datetime:Creado',
					'updated_at:datetime:Actualizado',
				],
			]) ?>

		</div>
	</div>
</div>
