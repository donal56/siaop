<?php
/**
 * This is the template for generating the model class of a specified table.
 */

use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
<?php $webvimark = false; ?>
<?php foreach ($relations as $name => $relation): ?>
<?php if ($relation[1] == 'User'): ?>
<?php $webvimark = true; ?>
<?php else: ?>
use app\models\<?= $relation[1] ?>;
<?php endif; ?>
<?php endforeach; ?>
use yii\helpers\ArrayHelper;
<?php if($webvimark): ?>
use webvimark\modules\UserManagement\models\User;
<?php endif; ?>

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . " {\n" ?>

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * Gets query for [[<?= $name ?>]].
     *
     * @return <?= $relationsClassHints[$name] . "\n" ?>
     */
    public function get<?= $name ?>() {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>

    public static function generateDropdownData() {
        return ArrayHelper::map(
            <?= $className ?>::find()<?= in_array('activo', array_keys($properties)) ? "->where(['activo' => 1])" : "" ?>->orderBy(['<?= $generator->nameField ?>' => SORT_ASC])->all(), 
                '<?= $pks[0] ?>', 
                '<?= $generator->nameField ?>'
            );
    }

    public function beforeSave($insert) {
<?php 
    if(isset($properties['fecha_version'])) {
        echo "        \$this->fecha_version = date('Y-m-d H:i:s');\n";
    }

    if(isset($properties['usuario_version'])) {
        echo "        \$this->usuario_version = Yii::\$app->user->identity->id;\n";
    }

    if(isset($properties['id_empresa'])) {
        echo "        \$this->id_empresa = Yii::\$app->user->identity->id_empresa;\n";
    } 
?>
        return parent::beforeSave($insert);
    }
}
