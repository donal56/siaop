<?php
/**
 * @var $this yii\web\View
 * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
 */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use app\assets\JQueryUIAsset;

JQueryUIAsset::register($this);
?>

<div id="changelog" style= 'visibility: hidden'>
    <h5 id= "changelog-title">
        <span class='ml-2'>Registro de cambios</span>&nbsp;&nbsp;&nbsp;
        <i class='fas fa-map-marked-alt pull-right'></i>
    </h5>
    <div id= "changelog-body">
        <?= $changelog ?>
    </div>
</div>

<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <a href="/"><img src="/img/logo.png" alt=""></a>
                                </div>

                                <h4 class="text-center mb-4">Iniciar sesión</h4>

                                <?php $form = ActiveForm::begin([
                                    'options' => ['autocomplete' => 'off'],
                                    'validateOnBlur' => false,
                                    'fieldClass' => 'app\components\CustomActiveField'
                                ]) ?>

                                <?= $form->field($model, 'username')->textInput(['autocomplete' => 'off']) ?>

                                <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']) ?>

                                <div class="row d-flex justify-content-between mt-4 mb-2">
                                    <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? 
                                        $form->field($model, 'rememberMe')->checkbox(['value'=>true]) : '' ?>
                                </div>

                                <div class="text-center">
                                    <?= Html::submitButton('Iniciar sesión', ['class' => 'btn btn-primary btn-block']) ?>
                                </div>

                                <?php ActiveForm::end() ?>

			                    <?= Html::a(Html::img('@web/img/gplay-badge.png', [
                                    'style' => 'width: 200px; left: 50%; position: relative; display: inline-block; transform: translate(-50%)'
                                ]), '/site/download', []) ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$css = <<<CSS

    #changelog {
        width: 25vw;
        font-size: 11px;
        position: fixed;
    }
    
    #changelog-title {
        padding: 7px 8px;
        font-size: 12px;
        background-color: var(--primary);
        margin: 0;
        font-weight: bold;
        cursor: pointer;
        color: white;
        border-bottom-right-radius: 10px;
        box-shadow: 0 0 2.1875rem 0 rgb(154 161 171 / 15%);
    }

    #changelog-body {
        position: relative;
        overflow: hidden;
        padding-left: 15px;
        padding-right: 5px;
        padding-top: 5px;
        overflow-y: auto;
        max-height: 350px;
        background-color: white;
        font-size: 11px;
        border-top-right-radius: 15px;
        border-bottom-right-radius: 15px;
        box-shadow: 0 0 2.1875rem 0 rgb(154 161 171 / 15%);
    }
CSS;

$this->registerCss($css);

$js = <<<JS
    $('#changelog').accordion({
        collapsible: true,
        active : false,
    })
    .css('visibility', 'unset');

    document.getElementById("loginform-username").focus();
JS;

$this->registerJs($js, $this::POS_END, "accordion");