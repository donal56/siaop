<?php

use app\components\Utils\ArrayUtils;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$permissionName = str_replace(' ', '', ucfirst(Inflector::camel2words(StringHelper::basename($generator->modelClass))));

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->nameField ?>;
\yii\web\YiiAsset::register($this);
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= "<?= " ?>Html::encode($this->title) ?></h4>
            <br>
            <div class= "btn-page">
                <?="<?php \n" ?>
                    if(User::hasPermission('modificar<?= $permissionName ?>')) { 
                        echo Html::button(Html::a('Actualizar', ['update', 'id' => $model-><?= $nameAttribute ?>]), ['class' => 'btn btn-primary']) . " ";
                    }
                    
                    if (User::hasPermission('eliminar<?= $permissionName ?>')) { 
                        echo Html::button(Html::a('Eliminar', ['delete', 'id' => $model-><?= $nameAttribute ?>], [
                            'data' => [
                                'confirm' => '¿Esta seguro de eliminar este registro?',
                                'method' => 'post',
                            ],
                        ]), ['class' => 'btn btn-danger']) . " ";
                    } 

                    if(User::hasPermission('agregar<?= $permissionName ?>')) { 
                        echo Html::button(Html::a('Crear <?= strtolower(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>', ['create']), ['class' => 'btn btn-success']) . ' ';
                    }

                    echo Html::button(Html::a('Regresar', ['/<?= str_replace("_", "-", $generator->tableName) ?>']), ['class' => 'btn btn-light']);
                ?>
                </div>
        </div>
        <div class="card-body">
            <?= "<?= " ?>DetailView::widget([
                'model' => $model,
                'attributes' => [
<?php
    foreach ($columns as $column) {
        if ($column->autoIncrement) continue;
        if ($column->name == "id_empresa") continue;
        
        if ($column->name == "fecha_version") {
            echo "                   [\n";
            echo "                      'attribute' => 'fecha_version',\n";
            echo "                      'value' => function(\$model) {\n";
            echo "                          \$fechaVersionDateTime = DateTime::createFromFormat('Y-m-d H:i:s.u', \$model->fecha_version);\n";
            echo "                          \$formatter = new \IntlDateFormatter('es_MX', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);\n";
            echo "                          \$formatter->setPattern('dd \'de\' MMMM \'de\' YYYY');\n";
            echo "                          return \$formatter->format(\$fechaVersionDateTime);\n";
            echo "                       }\n";
            echo "                   ],\n";
        }
        else if ($column->name == "activo") {
            echo "                   [\n";
            echo "                      'attribute' => 'activo',\n";
            echo "                      'value' => fn(\$model) => \$model->activo == 1 ? 'Sí' : 'No'\n";
            echo "                   ],\n";
        }
        else if($column->name == 'usuario_version') {
            echo "                   'usuarioVersion.username',\n";
        }
        else if(in_array($column->name, $foreignKeys)) {
            $relation = ArrayUtils::find($relations, fn($relation) => $relation[3] == $column->name);

            if($relation == null) {
                echo "<br>";
                echo "<br>";
                echo $column->name;
                echo "<br>";
                echo "<pre>";
                print_r($relations);
                echo "</pre>";
                die();
            }



            $columnName =  lcfirst($relation[1]) . "." . str_replace("id_", "", $column->name);
            echo "                   '" . $columnName . "',\n";
        }
        else {
            echo "                   '" . $column->name . "',\n";
        }
    }
?>
                ],
            ]) ?>
        </div>
    </div>
</div>
