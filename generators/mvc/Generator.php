<?php

namespace app\generators\mvc;

use Yii;
use app\components\Utils\ArrayUtils;
use app\components\Utils\StringUtils;
use yii\base\NotSupportedException;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

class Generator extends \yii\gii\Generator {

    /**
     * Campo descripción de la tabla
     */
    public $nameField;

    /**
     * Constantes
     */
    const RELATIONS_NONE = 'none';
    const RELATIONS_ALL = 'all';
    const RELATIONS_ALL_INVERSE = 'all-inverse';
    const FIELDGROUP_CONFIG = "['options' => ['class' => 'form-group col-sm-4']]";
    const CHECKBOX_FIELDGROUP_CONFIG = "['options' => ['class' => 'form-group col-sm-4 form-check custom-checkbox checkbox-info']]";
    const FIELD_CONFIG = "'class' => 'form-control'";
    const CHECKBOX_FIELD_CONFIG = "'class' => 'form-check-input', 'labelOptions' => ['style' => 'line-height: 23px']";

    /**
     * Modelo
     */
    public $db = 'db';
    public $ns = 'app\models';
    public $tableName;
    public $baseClass = 'yii\db\ActiveRecord';
    public $generateRelations = self::RELATIONS_ALL;
    public $generateRelationsFromCurrentSchema = true;
    public $generateLabelsFromComments = false;
    public $useTablePrefix = false;
    public $standardizeCapitals = true;
    public $singularize = true;
    public $useSchemaName = true;
    public $generateQuery = false;
    public $queryNs = 'app\models';
    public $queryClass;
    public $queryBaseClass = 'yii\db\ActiveQuery';

    /**
     * Controlador y vistas
     */
    public $modelClass; 
    public $controllerClass;
    public $viewPath;
    public $baseControllerClass = 'webvimark\components\BaseController';
    public $indexWidgetType = 'grid';
    public $searchModelClass = '';
    public $enablePjax = true;
    public $strictInflector = true;
    
    protected $tableNames;
    protected $classNames;
    
    public function init() {
        if(isset($_GET["tableName"])) {
            $this->tableName = $_GET["tableName"];
        }
        parent::init();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'Generador de MVC';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() {
        return 'Este generador retorna un modelo, controlador y vista para una tabla de la base de datos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['tableName', 'nameField', 'modelClass'], 'filter', 'filter' => 'trim'],
            [['tableName', 'nameField', 'modelClass'], 'required'],
            [['tableName'], 'validateTableName'],
            [['modelClass'], 'validateModelClass'],
            [['modelClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Solo se permiten letras, números y algunos simbolos de puntuación'],
            [['tableName'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Solo se permiten letras, números y algunos simbolos de puntuación'],
            // [['indexWidgetType'], 'in', 'range' => ['grid', 'list']],
            // [['generateRelations'], 'in', 'range' => [self::RELATIONS_NONE, self::RELATIONS_ALL, self::RELATIONS_ALL_INVERSE]],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {

        return array_merge(parent::attributeLabels(), [
            'tableName' => 'Tabla',
            'modelClass' => 'Nombre del modelo',
            'fieldName' => 'Campo principal'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function hints() {
        return parent::hints();
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates() {
        return ['controller.php', 'model.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes() {
        return array_merge(parent::stickyAttributes(), [
            'baseControllerClass', 
            'indexWidgetType',
            'ns', 
            'db', 
            'baseClass', 
            'generateRelations', 
            'generateLabelsFromComments', 
            'queryNs', 
            'queryBaseClass', 
            'useTablePrefix', 
            'generateQuery'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function autoCompleteData() {
        $db = $this->getDbConnection();
        if ($db !== null) {
            $data = [
                'tableName' => function() use($db) {
                    return $db->getSchema()->getTableNames();
                },
            ];

            if(!empty($this->tableName)) {

                $data["nameField"] = function() use($db) {
                    $table = $db->getSchema()->getTableSchema($this->tableName);

                    if($table == null) {
                        return [];
                    }
                    else {
                        return ArrayHelper::getColumn($table->columns, 'name', false);
                    }
                };

                $title = StringUtils::capitalizeWords(str_replace("_", " ", $this->tableName));
                $this->modelClass = str_replace(" ", "", StringUtils::singularize($title));
            }

            return $data;
        }

        return [];
    }

    /**
     * Returns the `tablePrefix` property of the DB connection as specified
     *
     * @return string
     * @since 2.0.5
     * @see getDbConnection
     */
    public function getTablePrefix() {
        $db = $this->getDbConnection();
        if ($db !== null) {
            return $db->tablePrefix;
        }

        return '';
    }

    public function validateModelClass() {

        if ($this->isReservedKeyword($this->modelClass)) {
            $this->addError('modelClass', 'El nombre de la clase no puede ser una palabra reservada por PHP.');
        }
        if ((empty($this->tableName) || substr_compare($this->tableName, '*', -1, 1)) && $this->modelClass == '') {
            $this->addError('modelClass', 'La clase del modelo no puede estar vacia si el nombre de la tabla no termina en asterisco.');
        }
    }

    /**
     * Validates the [[db]] attribute.
     */
    public function validateDb() {

        if (!Yii::$app->has($this->db)) {
            $this->addError('db', 'There is no application component named "db".');
        } elseif (!Yii::$app->get($this->db) instanceof Connection) {
            $this->addError('db', 'The "db" application component must be a DB connection instance.');
        }
    }

    /**
     * Validates the namespace.
     *
     * @param string $attribute Namespace variable.
     */
    public function validateNamespace($attribute) {
       
        $value = $this->$attribute;
        $value = ltrim($value, '\\');
        $path = Yii::getAlias('@' . str_replace('\\', '/', $value), false);
        if ($path === false) {
            $this->addError($attribute, 'Namespace must be associated with an existing directory.');
        }
    }

    /**
     * Validates the [[tableName]] attribute.
     */
    public function validateTableName() {

        if (strpos($this->tableName, '*') !== false && substr_compare($this->tableName, '*', -1, 1)) {
            $this->addError('tableName', 'Asterisk is only allowed as the last character.');

            return;
        }
        $tables = $this->getTableNames();
        if (empty($tables)) {
            $this->addError('tableName', "Table '{$this->tableName}' does not exist.");
        } else {
            foreach ($tables as $table) {
                $class = $this->generateClassName($table);
                if ($this->isReservedKeyword($class)) {
                    $this->addError('tableName', "Table '$table' will generate a class which is a reserved PHP keyword.");
                    break;
                }
            }
        }
    }

    /**
     * Generates the table name by considering table prefix.
     * If [[useTablePrefix]] is false, the table name will be returned without change.
     * @param string $tableName the table name (which may contain schema prefix)
     * @return string the generated table name
     */
    public function generateTableName($tableName) {
        
        if (!$this->useTablePrefix) {
            return $tableName;
        }

        $db = $this->getDbConnection();
        if (preg_match("/^{$db->tablePrefix}(.*?)$/", $tableName, $matches)) {
            $tableName = '{{%' . $matches[1] . '}}';
        } elseif (preg_match("/^(.*?){$db->tablePrefix}$/", $tableName, $matches)) {
            $tableName = '{{' . $matches[1] . '%}}';
        }
        return $tableName;
    }

    /**
     * {@inheritdoc}
     */
    public function generate() {
        
        /**
         * Datos automáticos
         */
        $pluralClassName = $this->generateControllerNameFromSnakeCase($this->tableName);
        $this->queryClass = "app\\models\\" . $this->modelClass . "Search";
        $this->controllerClass = "app\\controllers\\" . $pluralClassName . "Controller";
        $this->modelClass = "app\\models\\" . $this->modelClass;
        $this->searchModelClass = $this->modelClass . "Search";

        /**
         * Constantes
         */
        $db = $this->getDbConnection();
        $relations = $this->generateRelations();
        $tableSchema = $db->getTableSchema($this->tableName);
        $pks = $tableSchema->primaryKey;
        $columns = $tableSchema->columns;
        $tableRelations = isset($relations[$this->tableName]) ? $relations[$this->tableName] : [];
        
        /**
         * Modelo
         */
        $files = [$this->generateModel($db, $this->tableName, $pks, $relations)];

        /**
         * Conrolador
         */
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');
        $params = [
            'pks' => $pks,
            'columnNames' => $tableSchema->getColumnNames(),
            'urlParams' => $this->generateUrlParams($pks),
            'actionParams' => $this->generateActionParams($pks),
            'actionParamComments' => $this->generateActionParamComments($pks),
            'searchConditions' => $this->generateSearchConditions($tableSchema),
            'properties' => $this->generateProperties($tableSchema),
            'foreignKeys' => $this->getForeignKeys($tableSchema),
            'relations' => $tableRelations
        ];
        $files[] = new CodeFile($controllerFile, $this->render('controller.php', $params));

        /**
         * Modelo de búsqueda
         */

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php', $params));
        }

        /**
         * Vistas
         */
        $safeAttributesSet = [];
        foreach($this->generateRules($tableSchema) as $ruleAsString) {

            $regex = '/^\[\[(.*)\],.*\]$/m';
            preg_match_all($regex, $ruleAsString, $matches, PREG_SET_ORDER, 0);
            $fieldsString = $matches[0][1];
    
            $regex = '/\'.*?\'/m';
            preg_match_all($regex, $fieldsString, $matches, PREG_SET_ORDER, 0);
            
            $columnsAux = ArrayHelper::getColumn($matches, fn($match) => $match[0]);
            foreach($columnsAux as $columnAux) {
                $safeAttributesSet[$columnAux] = str_replace("'", "", $columnAux);
            }
        }
        unset($safeAttributesSet['usuario_version']);
        unset($safeAttributesSet['fecha_version']);
        $safeAttributesSet = array_values($safeAttributesSet);

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        $params = [
            'nameField' => $this->nameField,
            'safeAttributes' => $safeAttributesSet,
            'columns' => $columns,
            'urlParams' => $this->generateUrlParams($pks),
            'nameAttribute' => $pks[0],
            'foreignKeys' => $this->getForeignKeys($tableSchema),
            'relations' => $tableRelations
        ];
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file", $params));
            }
        }

        return $files;
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID() {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr(substr($this->controllerClass, $pos + 1), 0, -10);

        return Inflector::camel2id($class, '-', $this->strictInflector);
    }

    /**
     * @return string the controller view path
     */
    public function getViewPath() {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/views/' . $this->getControllerID());
        }

        return Yii::getAlias(str_replace('\\', '/', $this->viewPath));
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute) {

        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")->passwordInput([" . 
                    self::FIELD_CONFIG . "])";
            }

            return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")";
        }
        $column = $tableSchema->columns[$attribute];

        if ($column->phpType === 'boolean' || $column->type == "tinyint" || $column->name == "activo") {
            return "\$form->field(\$model, '$attribute', " . self::CHECKBOX_FIELDGROUP_CONFIG . ")->checkbox([" . 
                    self::CHECKBOX_FIELD_CONFIG . "])";
        }

        if ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")->textarea(['rows' => 6, " . 
            self::FIELD_CONFIG . "])";
        }

        if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
            $input = 'passwordInput';
        } else {
            $input = 'textInput';
        }

        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")\n                              " . 
                "->dropDownList(" . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''], " . 
                self::FIELD_CONFIG . "])";
        }

        if (str_starts_with($column->name, "id_")) {

            if(in_array($column->name, $tableSchema->primaryKey)) {
                return "\$form->field(\$model, '$attribute')->label(false)";
            }
            else {
                $relation = ArrayUtils::find($tableSchema->foreignKeys, fn($el) => isset($el[$attribute]));
                $foreignClass = $this->generateModelNameFromSnakeCase(ArrayUtils::getFirstValue($relation));
                $fullForeignClass = '\\app\\models\\' . $foreignClass;
                return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")\n                              " . 
                    "->dropDownList($fullForeignClass::generateDropdownData(), ['prompt' => '--Seleccione uno--', " . 
                    self::FIELD_CONFIG . "])";
            }
        }

        if ($column->phpType !== 'string' || $column->size === null) {
            return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")->$input([" . 
            self::FIELD_CONFIG . "])";
        }

        return "\$form->field(\$model, '$attribute', " . self::FIELDGROUP_CONFIG . ")\n" . 
                "                              ->$input(['maxlength' => true, " . 
                self::FIELD_CONFIG . "])";
    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute) {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }

        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }

        return "\$form->field(\$model, '$attribute')";
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column) {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        }

        if ($column->type === 'text') {
            return 'ntext';
        }

        if (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        }

        if (stripos($column->name, 'email') !== false) {
            return 'email';
        }

        if (preg_match('/(\b|[_-])url(\b|[_-])/i', $column->name)) {
            return 'url';
        }

        return 'text';
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules() {
        if (($table = $this->getTableSchema()) === false) {
            $db = $this->getDbConnection();
            $tableSchema = $db->getTableSchema($this->tableName);
            return ["[['" . implode("', '", $tableSchema->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels() {

        $labels = [];
        $db = $this->getDbConnection();
        $tableSchema = $db->getTableSchema($this->tableName);
        $attributeLabels = $this->generateLabels($tableSchema);

        foreach ($tableSchema->columns as $column) {
            if (isset($attributeLabels[$column->name])) {
                $labels[$column->name] = $attributeLabels[$column->name];
            } else {
                if (!strcasecmp($column->name, 'id')) {
                    $labels[$column->name] = 'ID';
                } else {
                    $label = Inflector::camel2words($column->name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
                    }
                    $labels[$column->name] = $label;
                }
            }
        }

        return $labels;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions($tableSchema) {
        $columns = [];
        foreach ($tableSchema->columns as $column) {
            $columns[$column->name] = $column->type;
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                default:
                    $likeKeyword = $this->getDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', '{$column}', \$this->{$column}])";                    
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams($pks) {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            }

            return "'id' => \$model->{$pks[0]}";
        }

        $params = [];
        foreach ($pks as $pk) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                $params[] = "'$pk' => (string)\$model->$pk";
            } else {
                $params[] = "'$pk' => \$model->$pk";
            }
        }

        return implode(', ', $params);
    }

    /**
     * Generates action parameters
     * @return string
     */
    public function generateActionParams($pks) {
        /* @var $class ActiveRecord */
        if (count($pks) === 1) {
            return '$id';
        }

        return '$' . implode(', $', $pks);
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function generateActionParamComments($pks) {
        /* @var $class ActiveRecord */
        if (($table = $this->getTableSchema()) === false) {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . (strtolower(substr($pk, -2)) === 'id' ? 'integer' : 'string') . ' $' . $pk;
            }

            return $params;
        }
        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        }

        $params = [];
        foreach ($pks as $pk) {
            $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
        }

        return $params;
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return bool|\yii\db\TableSchema
     */
    public function getTableSchema() {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        }

        return false;
    }

    /**
     * @return array model column names
     */
    public function getColumnNames() {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        }

        /* @var $model \yii\base\Model */
        $model = new $class();

        return $model->attributes();
    }

    /**
     * Generates the attribute labels for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated attribute labels (name => label)
     */
    public function generateLabels($table) {

        $labels = [];
        foreach ($table->columns as $column) {
            if($this->generateLabelsFromComments && !empty($column->comment)) {
                $labels[$column->name] = $column->comment;
            } 
            elseif($column->name == 'fecha_version') {
                $labels[$column->name] = 'Última fecha de modificación';
            } 
            elseif($column->name == 'usuario_version') {
                $labels[$column->name] = 'Último usuario de modificación';
            } 
            elseif($column->name == "id_" . str_replace(" ", "_", StringUtils::singularize(str_replace("_", " ", $table->name)))) {
                $labels[$column->name] = "ID";
            } 
            elseif(StringUtils::startsWith($column->name, 'id_')) {
                $label = str_replace("id_", "", $column->name);
                $label = StringUtils::capitalizeWord(str_replace("_", " de ", $label));
                $labels[$column->name] = $label;
            } 
            else {
                $label = Inflector::camel2words($column->name);
                if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                    $label = substr($label, 0, -3) . ' ID';
                }
                $labels[$column->name] = $label;
            }
        }

        return $labels;
    }

    /**
     * Generates the relation class hints for the relation methods
     * @param array $relations the relation array for single table
     * @param bool $generateQuery generates ActiveQuery class (for ActiveQuery namespace available)
     * @return array
     * @since 2.1.4
     */
    public function generateRelationsClassHints($relations, $generateQuery) {
        $result = [];
        foreach ($relations as $name => $relation){
            // The queryNs options available if generateQuery is active
            if ($generateQuery) {
                $queryClassRealName = '\\' . $this->queryNs . '\\' . $relation[1];
                if (class_exists($queryClassRealName, true) && is_subclass_of($queryClassRealName, '\yii\db\BaseActiveRecord')) {
                    /** @var \yii\db\ActiveQuery $activeQuery */
                    $activeQuery = $queryClassRealName::find();
                    $activeQueryClass = $activeQuery::class;
                    if (strpos($activeQueryClass, $this->ns) === 0){
                        $activeQueryClass = StringHelper::basename($activeQueryClass);
                    }
                    $result[$name] = '\yii\db\ActiveQuery|' . $activeQueryClass;
                } else {
                    $result[$name] = '\yii\db\ActiveQuery|' . (($this->ns === $this->queryNs) ? $relation[1]: '\\' . $this->queryNs . '\\' . $relation[1]) . 'Query';
                }
            } else {
                $result[$name] = '\yii\db\ActiveQuery';
            }
        }
        return $result;
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table) {

        $types = [];
        $lengths = [];
        foreach ($table->columns as $column) {

            if(in_array($column->name, ['id_empresa', 'fecha_version', 'usuario_version'])) {
                continue;
            }

            if ($column->autoIncrement) {
                continue;
            }

            if (!$column->allowNull && $column->defaultValue === null) {
                $types['required'][] = $column->name;
            }
            
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                case Schema::TYPE_JSON:
                    $types['safe'][] = $column->name;
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
        }
        $rules = [];
        $driverName = $this->getDbDriverName();
        foreach ($types as $type => $columns) {
            if ($driverName === 'pgsql' && $type === 'integer') {
                $rules[] = "[['" . implode("', '", $columns) . "'], 'default', 'value' => null]";
            }
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], 'string', 'max' => $length]";
        }

        $db = $this->getDbConnection();

        // Unique indexes rules
        try {
            $uniqueIndexes = array_merge($db->getSchema()->findUniqueIndexes($table), [$table->primaryKey]);
            $uniqueIndexes = array_unique($uniqueIndexes, SORT_REGULAR);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {

                    $uniqueColumns = array_filter($uniqueColumns, function($column) {
                        return !in_array($column, ['id_empresa', 'fecha_version', 'usuario_version']);
                    });

                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount === 1) {
                        $rules[] = "[['" . $uniqueColumns[0] . "'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[] = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList']]";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $refs) {
            $refTable = $refs[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            $refClassName = $this->generateClassName($refTable);
            if($refClassName == "Usuario") $refClassName = "User";
            unset($refs[0]);
            $attributes = array_filter(array_keys($refs), function($column) {
                return !in_array($column, ['id_empresa', 'fecha_version', 'usuario_version']);
            });

            if(count($attributes) > 0) {
                $attributes = implode("', '", $attributes);
                $targetAttributes = [];
                foreach ($refs as $key => $value) {
                    $targetAttributes[] = "'$key' => '$value'";
                }
                $targetAttributes = implode(', ', $targetAttributes);
                $rules[] = "[['$attributes'], 'exist', 'skipOnError' => true, 'targetClass' => $refClassName::class, 'targetAttribute' => [$targetAttributes]]";
            }
        }

        return $rules;
    }

    /**
     * Generates the properties for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated properties (property => type)
     * @since 2.0.6
     */
    protected function generateProperties($table) {
        $properties = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                    $type = 'int';
                    break;
                case Schema::TYPE_BOOLEAN:
                    $type = 'bool';
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $type = 'float';
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                case Schema::TYPE_JSON:
                    $type = 'string';
                    break;
                default:
                    $type = $column->phpType;
            }
            if ($column->allowNull){
                $type .= '|null';
            }
            $properties[$column->name] = [
                'type' => $type,
                'name' => $column->name,
                'comment' => $column->comment,
            ];
        }

        return $properties;
    }

    /**
     * @return string[] all db schema names or an array with a single empty string
     * @throws NotSupportedException
     * @since 2.0.5
     */
    protected function getSchemaNames() {

        $db = $this->getDbConnection();

        if ($this->generateRelationsFromCurrentSchema) {
            if ($db->schema->defaultSchema !== null) {
                return [$db->schema->defaultSchema];
            }
            return [''];
        }

        $schema = $db->getSchema();
        if ($schema->hasMethod('getSchemaNames')) { // keep BC to Yii versions < 2.0.4
            try {
                $schemaNames = $schema->getSchemaNames();
            } catch (NotSupportedException $e) {
                // schema names are not supported by schema
            }
        }
        if (!isset($schemaNames)) {
            if (($pos = strpos($this->tableName, '.')) !== false) {
                $schemaNames = [substr($this->tableName, 0, $pos)];
            } else {
                $schemaNames = [''];
            }
        }
        return $schemaNames;
    }

    /**
     * @return array the generated relation declarations
     */
    protected function generateRelations() {

        if ($this->generateRelations === self::RELATIONS_NONE) {
            return [];
        }

        $db = $this->getDbConnection();
        $relations = [];
        $schemaNames = $this->getSchemaNames();
        foreach ($schemaNames as $schemaName) {
            foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
                $className = $this->generateClassName($table->fullName);
                foreach ($table->foreignKeys as $refs) {
                    $refTable = $refs[0];
                    $column = ArrayUtils::getLastKey($refs);

                    $refTableSchema = $db->getTableSchema($refTable);
                    if ($refTableSchema === null) {
                        // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                        continue;
                    }
                    unset($refs[0]);
                    $fks = array_keys($refs);
                    $refClassName = $this->generateClassName($refTable);
                    if($refClassName == "Usuario") $refClassName = "User";

                    // Add relation for this table
                    $link = $this->generateRelationLink(array_flip($refs));
                    $relationName = $this->generateRelationName($relations, $table, $fks[0], false);
                    if($relationName) {
                        $relations[$table->fullName][$relationName] = [
                            "return \$this->hasOne($refClassName::class, $link);",
                            $refClassName,
                            false,
                            $column
                        ];
                    }

                    // Add relation for the referenced table
                    $hasMany = $this->isHasManyRelation($table, $fks);
                    $link = $this->generateRelationLink($refs);
                    $relationName = $this->generateRelationName($relations, $refTableSchema, $className, $hasMany);
                    if($relationName) {
                        $relations[$refTableSchema->fullName][$relationName] = [
                            "return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "($className::class, $link);",
                            $className,
                            $hasMany,
                            $column
                        ];
                    }
                }

                if (($junctionFks = $this->checkJunctionTable($table)) === false) {
                    continue;
                }

                $relations = $this->generateManyManyRelations($table, $junctionFks, $relations);
            }
        }

        if ($this->generateRelations === self::RELATIONS_ALL_INVERSE) {
            return $this->addInverseRelations($relations);
        }

        return $relations;
    }

    /**
     * Adds inverse relations
     *
     * @param array $relations relation declarations
     * @return array relation declarations extended with inverse relation names
     * @since 2.0.5
     */
    protected function addInverseRelations($relations) {

        $db = $this->getDbConnection();
        $relationNames = [];

        $schemaNames = $this->getSchemaNames();
        foreach ($schemaNames as $schemaName) {
            foreach ($db->schema->getTableSchemas($schemaName) as $table) {
                $className = $this->generateClassName($table->fullName);
                foreach ($table->foreignKeys as $refs) {
                    $refTable = $refs[0];
                    $column = ArrayUtils::getLastKey($refs);

                    $refTableSchema = $db->getTableSchema($refTable);
                    if ($refTableSchema === null) {
                        // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                        continue;
                    }
                    unset($refs[0]);
                    $fks = array_keys($refs);

                    $leftRelationName = $this->generateRelationName($relationNames, $table, $fks[0], false);
                    if($leftRelationName) {
                        $relationNames[$table->fullName][$leftRelationName] = true;
                    }

                    $hasMany = $this->isHasManyRelation($table, $fks);

                    $rightRelationName = $this->generateRelationName($relationNames, $refTableSchema, $className, $hasMany);
                    if($rightRelationName) {
                        $relationNames[$refTableSchema->fullName][$rightRelationName] = true;
                    }

                    if($leftRelationName) {
                        $relations[$table->fullName][$leftRelationName][0] =
                        rtrim($relations[$table->fullName][$leftRelationName][0], ';')
                        . "->inverseOf('".lcfirst($rightRelationName)."');";
                    }
                    
                    if($rightRelationName) {
                        $relations[$refTableSchema->fullName][$rightRelationName][0] =
                            rtrim($relations[$refTableSchema->fullName][$rightRelationName][0], ';')
                            . "->inverseOf('".lcfirst($leftRelationName)."');";
                    }
                }
            }
        }
        return $relations;
    }

    /**
     * Determines if relation is of has many type
     *
     * @param TableSchema $table
     * @param array $fks
     * @return bool
     * @since 2.0.5
     */
    protected function isHasManyRelation($table, $fks) {
        $uniqueKeys = [$table->primaryKey];
        try {
            $uniqueKeys = array_merge($uniqueKeys, $this->getDbConnection()->getSchema()->findUniqueIndexes($table));
        } catch (NotSupportedException $e) {
            // ignore
        }
        foreach ($uniqueKeys as $uniqueKey) {
            if (count(array_diff(array_merge($uniqueKey, $fks), array_intersect($uniqueKey, $fks))) === 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Generates the link parameter to be used in generating the relation declaration.
     * @param array $refs reference constraint
     * @return string the generated link parameter.
     */
    protected function generateRelationLink($refs) {
        $pairs = [];
        foreach ($refs as $a => $b) {
            $pairs[] = "'$a' => '$b'";
        }

        return '[' . implode(', ', $pairs) . ']';
    }

    /**
     * Checks if the given table is a junction table, that is it has at least one pair of unique foreign keys.
     * @param \yii\db\TableSchema the table being checked
     * @return array|bool all unique foreign key pairs if the table is a junction table,
     * or false if the table is not a junction table.
     */
    protected function checkJunctionTable($table) {

        if (count($table->foreignKeys) < 2) {
            return false;
        }
        $uniqueKeys = [$table->primaryKey];
        try {
            $uniqueKeys = array_merge($uniqueKeys, $this->getDbConnection()->getSchema()->findUniqueIndexes($table));
        } catch (NotSupportedException $e) {
            // ignore
        }
        $result = [];
        // find all foreign key pairs that have all columns in an unique constraint
        $foreignKeys = array_values($table->foreignKeys);
        $foreignKeysCount = count($foreignKeys);

        for ($i = 0; $i < $foreignKeysCount; $i++) {
            $firstColumns = $foreignKeys[$i];
            unset($firstColumns[0]);

            for ($j = $i + 1; $j < $foreignKeysCount; $j++) {
                $secondColumns = $foreignKeys[$j];
                unset($secondColumns[0]);

                $fks = array_merge(array_keys($firstColumns), array_keys($secondColumns));
                foreach ($uniqueKeys as $uniqueKey) {
                    if (count(array_diff(array_merge($uniqueKey, $fks), array_intersect($uniqueKey, $fks))) === 0) {
                        // save the foreign key pair
                        $result[] = [$foreignKeys[$i], $foreignKeys[$j]];
                        break;
                    }
                }
            }
        }
        return empty($result) ? false : $result;
    }

    /**
     * Generate a relation name for the specified table and a base name.
     * @param array $relations the relations being generated currently.
     * @param \yii\db\TableSchema $table the table schema
     * @param string $key a base name that the relation name may be generated from
     * @param bool $multiple whether this is a has-many relation
     * @return string the relation name
     */
    protected function generateRelationName($relations, $table, $columnName, $multiple) {

        $relationName = StringUtils::startsWith($columnName, "id_") ? substr($columnName, 3) : $columnName;
        $relationName = StringUtils::pluralize(str_replace("_", " ", $relationName));

        if($multiple) {
            $relationName = $this->generateControllerNameFromSnakeCase($relationName);
        }
        else {
            $relationName = $this->generateModelNameFromSnakeCase($relationName);
        }

        return $relationName;
    }

     /**
     * @return array the table names that match the pattern specified by [[tableName]].
     */
    protected function getTableNames() {

        if ($this->tableNames !== null) {
            return $this->tableNames;
        }
        $db = $this->getDbConnection();
        if ($db === null) {
            return [];
        }
        $tableNames = [];
        if (strpos($this->tableName, '*') !== false) {
            if (($pos = strrpos($this->tableName, '.')) !== false) {
                $schema = substr($this->tableName, 0, $pos);
                $pattern = '/^' . str_replace('*', '\w+', substr($this->tableName, $pos + 1)) . '$/';
            } else {
                $schema = '';
                $pattern = '/^' . str_replace('*', '\w+', $this->tableName) . '$/';
            }

            foreach ($db->schema->getTableNames($schema) as $table) {
                if (preg_match($pattern, $table)) {
                    $tableNames[] = $schema === '' ? $table : ($schema . '.' . $table);
                }
            }
        } elseif (($table = $db->getTableSchema($this->tableName, true)) !== null) {
            $tableNames[] = $this->tableName;
            $this->classNames[$this->tableName] = $this->modelClass;
        }

        return $this->tableNames = $tableNames;
    }

    /**
     * Generates a class name from the specified table name.
     * @param string $tableName the table name (which may contain schema prefix)
     * @param bool $useSchemaName should schema name be included in the class name, if present
     * @return string the generated class name
     */
    protected function generateClassName($tableName, $useSchemaName = null) {
        return $this->generateModelNameFromSnakeCase($tableName);
    }

    /**
     * @return Connection the DB connection as specified by [[db]].
     */
    protected function getDbConnection() {
        return Yii::$app->get($this->db, false);
    }

    /**
     * @return string|null driver name of db connection.
     * In case db is not instance of \yii\db\Connection null will be returned.
     * @since 2.0.6
     */
    protected function getDbDriverName() {
        /** @var Connection $db */
        $db = $this->getDbConnection();
        return $db instanceof \yii\db\Connection ? $db->driverName : null;
    }

    /**
     * Checks if any of the specified columns is auto incremental.
     * @param \yii\db\TableSchema $table the table schema
     * @param array $columns columns to check for autoIncrement property
     * @return bool whether any of the specified columns is auto incremental.
     */
    protected function isColumnAutoIncremental($table, $columns) {
        foreach ($columns as $column) {
            if (isset($table->columns[$column]) && $table->columns[$column]->autoIncrement) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generates relations using a junction table by adding an extra viaTable().
     * @param \yii\db\TableSchema the table being checked
     * @param array $fks obtained from the checkJunctionTable() method
     * @param array $relations
     * @return array modified $relations
     */
    private function generateManyManyRelations($table, $fks, $relations) {
    
        $db = $this->getDbConnection();

        foreach ($fks as $pair) {

            $column = ArrayUtils::getLastKey($pair);

            list($firstKey, $secondKey) = $pair;
            $table0 = $firstKey[0];
            $table1 = $secondKey[0];
            unset($firstKey[0], $secondKey[0]);
            $className0 = $this->generateClassName($table0);
            $className1 = $this->generateClassName($table1);
            $table0Schema = $db->getTableSchema($table0);
            $table1Schema = $db->getTableSchema($table1);

            // @see https://github.com/yiisoft/yii2-gii/issues/166
            if ($table0Schema === null || $table1Schema === null) {
                continue;
            }

            $link = $this->generateRelationLink(array_flip($secondKey));
            $viaLink = $this->generateRelationLink($firstKey);
            $relationName = $this->generateRelationName($relations, $table0Schema, key($secondKey), true);
            if($relationName) {
                $relations[$table0Schema->fullName][$relationName] = [
                    "return \$this->hasMany($className1::class, $link)\n" . 
                        "                   ->viaTable('"
                    . $this->generateTableName($table->name) . "', $viaLink);",
                    $className1,
                    true,
                    $column
                ];
            }

            $link = $this->generateRelationLink(array_flip($firstKey));
            $viaLink = $this->generateRelationLink($secondKey);
            $relationName = $this->generateRelationName($relations, $table1Schema, key($firstKey), true);
            if($relationName) {
                $relations[$table1Schema->fullName][$relationName] = [
                    "return \$this->hasMany($className0::class, $link)\n" . 
                    "                   ->viaTable('"
                    . $this->generateTableName($table->name) . "', $viaLink);",
                    $className0,
                    true,
                    $column
                ];
            }
        }

        return $relations;
    }

    /**
     * Recibiendo un nombre en minusculas, snake_case y singular, devuelve el nombre del modelo
     * P/E: unidades_medidad => UnidadMedida
     */
    public function generateModelNameFromSnakeCase($tableName) {
        return str_replace(" ", "", 
            StringUtils::singularize(
                StringUtils::capitalizeWords(
                    str_replace("_", " ", $tableName)
        )));
    }

    /**
     * Recibiendo un nombre en minusculas, snake_case y singular, devuelve el nombre del controlador 
     * P/E: unidades_medidad => UnidadesMedida
     */
    public function generateControllerNameFromSnakeCase($tableName) {
        return str_replace(" ", "", 
                StringUtils::capitalizeWords(
                    str_replace("_", " ", $tableName)
        ));
    }

    /**
     * Nuevo método a implementar reemplazando anteriores técnicas
     * Retorna un arreglo con las columnas de la tabla que forman parte de una relación foranea
     */
    public function getForeignKeys($tableSchema) {
        $fks = array_values($tableSchema->foreignKeys);
        array_walk($fks, function(&$relation) {
            $relation = ArrayUtils::getLastKey($relation);
        });
        return $fks;
    }

    public function generateModel($db, $tableName) {
        
        $tableSchema = $db->getTableSchema($tableName);
        $pks = $tableSchema->primaryKey;
        $relations = $this->generateRelations();
        $modelClassName = $this->generateClassName($tableName);
        $tableRelations = isset($relations[$tableName]) ? $relations[$tableName] : [];
        $params = [
            'tableName' => $tableName,
            'className' => $modelClassName,
            'tableSchema' => $tableSchema,
            'properties' => $this->generateProperties($tableSchema),
            'labels' => $this->generateLabels($tableSchema),
            'rules' => $this->generateRules($tableSchema),
            'relations' => $tableRelations,
            'pks' => $pks,
            'columnNames' => $tableSchema->getColumnNames(),
            'relationsClassHints' => $this->generateRelationsClassHints($tableRelations, $this->generateQuery),
        ];
        return new CodeFile(
            Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
            $this->render('model.php', $params)
        );
    }
}
