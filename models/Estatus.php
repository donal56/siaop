<?php

namespace app\models;

use Yii;
use app\models\TipoEstatu;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "estatus".
 *
 * @property int $id_estatus
 * @property string $estatus
 * @property int $id_tipo_estatus
 *
 * @property TipoEstatu $tipoEstatu
 */
class Estatus extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'estatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_estatus', 'estatus', 'id_tipo_estatus'], 'required'],
            [['id_estatus', 'id_tipo_estatus'], 'integer'],
            [['estatus'], 'string', 'max' => 255],
            [['id_estatus'], 'unique'],
            [['id_tipo_estatus'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEstatu::class, 'targetAttribute' => ['id_tipo_estatus' => 'id_tipo_estatus']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_estatus' => 'Estatus',
            'estatus' => 'Estatus',
            'id_tipo_estatus' => 'Tipo estatus',
        ];
    }

    /**
     * Gets query for [[TipoEstatu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoEstatu() {
        return $this->hasOne(TipoEstatu::class, ['id_tipo_estatus' => 'id_tipo_estatus']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            Estatus::find()->orderBy(['estatus' => SORT_ASC])->all(), 
            'id_estatus', 
            'estatus'
        );
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
