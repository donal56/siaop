<?php

use webvimark\modules\UserManagement\models\User;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use app\models\Centros;
use app\models\TiposUsuario;
use wbraganca\dynamicform\DynamicFormWidget;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 * @var yii\bootstrap\ActiveForm $form
 */

?>

<div class="user-form">

    <?php 
        $form = ActiveForm::begin([
            'id'=>'user',
            'fieldConfig' => [
                'template' => "<div class= 'form-group col-sm-4 col-xs-8'>{label}\n{input}\n{hint}\n{error}</div>",
                'options' => ['class' => 'row']
            ]
        ]);

        echo $form->field($model, 'nombre')
            ->textInput(['maxlength' => 100]);

        echo $form->field($model, 'apellido_paterno')
            ->textInput(['maxlength' => 100]);

        echo $form->field($model, 'apellido_materno')
            ->textInput(['maxlength' => 100]);

        echo $form->field($model, 'username')
            ->textInput(['maxlength' => 255, 'autocomplete'=>'off']);

        if($model->isNewRecord) {
            echo $form->field($model, 'password')
                ->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']);
            echo $form->field($model, 'repeat_password')
                ->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']);
        }

        if(User::hasPermission('bindUserToIp')) {
            echo $form->field($model, 'bind_to_ip')->textInput(['maxlength' => 255])
                ->hint('Por ejemplo: 123.34.56.78, 168.111.192.12');
        }

        if(User::hasPermission('editUserEmail')) {
            echo $form->field($model, 'email')
                ->textInput(['maxlength' => 255]);
        }
    ?>

    <?= $form->field($model->loadDefaultValues(), 'status', ['options' => ['style' => 'margin-left: 20px']])
            ->checkbox()
            ->label('Activo') ?>

    <br>

    <div class="form-group">
        <?php 
            if($model->isNewRecord) {
                echo Html::submitButton('<span class="glyphicon glyphicon-plus-sign"></span> Crear',['class' => 'btn btn-success']);
            }
            else {
                echo Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Guardar', ['class' => 'btn btn-primary']);
            }
        ?>
        <?= Html::a('Regresar', ['/user-management/user/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php BootstrapSwitch::widget() ?>
