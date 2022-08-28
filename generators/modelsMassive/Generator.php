<?php

namespace app\generators\modelsMassive;

use Yii;
use yii\helpers\ArrayHelper;

class Generator extends \yii\gii\Generator {

    public $tables = [];

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'Modelos masivos';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() {
        return 'Este generador retorna todos los modelos de la base de datos predeterminada';
    }

      /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tables'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ['tables' => 'Tablas'];
    }

    public function getDbTables() {
        $tables = $this->getDb()->getSchema()->getTableNames();
        return ArrayHelper::map($tables, fn($table) => $table, fn($table) => $table);
    }

    public function getDb() {
        return Yii::$app->get('db', false);
    }

    /**
     * {@inheritdoc}
     */
    public function generate() {
        
        $files = [];
        $generator = new \app\generators\mvc\Generator();

        foreach($this->tables as $table) {
            $files[] = $generator->generateModel($this->getDb(), $table);
        }

        return $files;
    }
}
