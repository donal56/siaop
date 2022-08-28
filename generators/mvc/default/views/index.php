<?php

use app\components\Utils\ArrayUtils;
use app\components\Utils\StringUtils;
use Prophecy\Util\StringUtil;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$permissionName = str_replace(' ', '', ucfirst(Inflector::camel2words(StringHelper::basename($generator->modelClass))));

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>
use webvimark\modules\UserManagement\models\User;


/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '<?= StringUtils::capitalizeWord(Inflector::pluralize(Inflector::titleize($generator->tableName))) ?>';
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div class="card">
        <div class="card-header d-block">
            <h4 class= "card-title"><?= "<?= " ?>Html::encode($this->title) ?></h4>
            <br>
            <?="<?php \n" ?>
                if(User::hasPermission('agregar<?= $permissionName ?>')) { 
                    echo '<div class= "btn-page">';
                        echo Html::button(Html::a(<?= $generator->generateString('Crear ' . strtolower(Inflector::camel2words(StringHelper::basename($generator->modelClass))))?> . '<span class="btn-icon-end"><i class="fa fa-plus"></i></span>', ['create']), ['class' => 'btn btn-success']);
                    echo '</div>';
                }
            ?>
        </div>
        <div class="card-body">
<?= $generator->enablePjax ? "            <?php Pjax::begin(); ?>\n" : '' ?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
            <?= "<?= " ?>GridView::widget([
                'dataProvider' => $dataProvider,
                <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n                'columns' => [\n" : "'columns' => [\n"; ?>
                    ['class' => 'yii\grid\SerialColumn'],
<?php
    $count = 0;
    foreach ($columns as $column) {

        if ($column->autoIncrement) continue;
        if ($column->name == "id_empresa") continue;
        if ($column->name == "usuario_version") continue;
        if ($column->name == "fecha_version") continue;

        if (++$count < 6) {
            
            if(in_array($column->name, $foreignKeys)) {
                $relation = ArrayUtils::find($relations, fn($relation) => $relation[3] == $column->name);
                $path =  lcfirst($relation[1]) . "->" . str_replace("id_", "", $column->name);

                echo "                    [\n";
                echo "                        'attribute' => '" . $column->name . "',\n";
                echo "                        'value' => fn(\$model) => \$model->" . $path . ",\n";
                echo "                        'filter' => app\\models\\" . $relation[1] . "::generateDropdownData()";
                echo "                   ],\n";
            }
            else {
                echo "                    [\n";
                echo "                        'attribute' => '" . $column->name . "',\n";
                echo "                        'format' => " . (
                    $column->phpType === 'boolean' || $column->type == "tinyint" ? 'fn($val) => $val == 1 ? "Sí" : "No"' : '\'text\''
                ) . ",\n";
                echo "                        'filterInputOptions' => [\n";
                echo "                             'class' => 'form-control',\n";
                echo "                             'placeholder' => '🔎︎',\n";
                echo "                       ],\n";
                echo "                   ],\n";
            }
        } 
        else {
            echo "                    //'" . $column->name . "',\n";
        }
    }
?>
                    ['class' => 'yii\grid\ActionColumn'],
                ],
                'tableOptions' => [
                    'class' => 'table table-striped table-hover table-responsive-sm'
                ]
            ]); ?>
<?php else: ?>
            <?= "<?= " ?>ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
                },
            ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? "            <?php Pjax::end(); ?>\n" : '' ?>
        </div>
    </div>
</div>
