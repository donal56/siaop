<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */

$permissionName = str_replace(' ', '', ucfirst(Inflector::camel2words(StringHelper::basename($generator->modelClass))));

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <?= "<?php " ?>$form = ActiveForm::begin(['id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form']); ?>
        <div class="card">
            <div class="card-header d-block">
                <h4 class= "card-title"><?= "<?= " ?>Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <?= "<?php " ?> if($guardado) : ?>
                    <div class="alert alert-success" role="alert">
                        <p><?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?> <b>"<?= "<?= " ?> $guardado ?>"</b> guardado exitosamente.</p>
                    </div>
                <?= "<?php " ?> endif; ?>

                <div class="row my-3">
<?php foreach ($safeAttributes as $attribute) {
    if(!in_array($attribute, ['usuario_version', 'fecha_version', 'id_empresa', 'activo'])) {
        echo "                       <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
                </div>
<?php 
    if(in_array('activo', $safeAttributes)) {
        echo "                   <div class= \"row my-3\">\n";
        echo "                       <?= " . $generator->generateActiveField('activo') . " ?>\n\n";
        echo "                   </div>\n";
    }
?>
                <br>
                <div class= "btn-page">
                    <?= "<?= " ?>Html::button(<?= $generator->generateString('Guardar') ?>, ['class' => 'btn btn-success',
                        'onclick' => 'saveSimpleForm("<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form", false)'
                    ]) ?>
                    <?="<?php \n" ?>
                        if(User::hasPermission('agregar<?= $permissionName ?>')) { 
                            echo Html::button(<?= $generator->generateString('Guardar y crear otro') ?>, ['class' => 'btn btn-primary', 'onclick' => 'saveSimpleForm("<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form", true)']) . ' ';
                        }
                    ?>
                    <?= "<?= " ?>Html::button(Html::a(<?= $generator->generateString('Regresar') ?>, ['index']), ['class' => 'btn btn-light']) ?>
                </div>
            <?= "<?php " ?>ActiveForm::end(); ?>
        </div>
    </div>
</div>
