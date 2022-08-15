<?php

namespace app\models;

use Yii;
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
            [['id_tipo_estatus', 'tipo_estatus'], 'required'],
            [['id_tipo_estatus'], 'integer'],
            [['tipo_estatus'], 'string', 'max' => 255],
            [['id_tipo_estatus'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_tipo_estatus' => 'Tipo estatus',
            'tipo_estatus' => 'Tipo estatus',
        ];
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            TipoEstatu::find()->orderBy(['tipo_estatus' => SORT_ASC])->all(), 
            'id_tipo_estatus', 
            'tipo_estatus'
        );
    }

    public function beforeSave($insert) {
                                return parent::beforeSave($insert);
    }
}
