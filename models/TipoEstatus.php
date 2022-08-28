<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tipos_estatus".
 *
 * @property int $id_tipo_estatus
 * @property string $tipo_estatus
 */
class TipoEstatus extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tipos_estatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tipo_estatus'], 'required'],
            [['tipo_estatus'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_tipo_estatus' => 'ID',
            'tipo_estatus' => 'Tipo de estatus',
        ];
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            TipoEstatus::find()->orderBy(['tipo_estatus' => SORT_ASC])->all(), 
            'id_tipo_estatus', 
            'tipo_estatus'
        );
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
