<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\assets\GoogleMapsAsset;

    $this->title = "Parámetros";
    $form = ActiveForm::begin(['id' => 'parametros-id', 'options' => ['enctype' => 'multipart/form-data']]);
    $idEmpresa = $id;

    GoogleMapsAsset::register($this);   
?>

<h1><?= $this->title ?></h1>

<?php if(!empty($errors)) { ?>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Error</h4>
            <p>No se pudieron guardar los siguientes parámetros:</p>
            <hr>
            <p><ul>
                <?php
                    foreach ($errors as $codigo => $error) {
                        echo "<li style= 'margin-left: 10px'>$codigo: {$error[0][0]}</li>";
                    }
                ?>
            </ul></p>
        </div>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="parametros-form">
            <?php
                foreach ($parametros as $parametro) {
                    echo $parametro->htmlInput;
                }
            ?>
            <br>
            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <?php
                    if(isset($idEmpresa)) {
                        echo Html::a("Cancelar", ['/empresas/index', 'id' => $idEmpresa], ['class' => 'btn btn-danger']);
                    }
                    else {
                        echo Html::a("Cancelar", ['/user-management/user'], ['class' => 'btn btn-danger']);
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php 
    ActiveForm::end(); 
?>