<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "origenes".
 *
 * @property int $ori_clave
 * @property string $ori_nombre
 *
 * @property Ordenesservicio[] $ordenesservicios
 * @property Reportes[] $reportes
 */
class Origenes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'origenes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ori_nombre'], 'required'],
            [['ori_nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ori_clave' => 'Ori Clave',
            'ori_nombre' => 'Ori Nombre',
        ];
    }

    /**
     * Gets query for [[Ordenesservicios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenesservicios()
    {
        return $this->hasMany(Ordenesservicio::class, ['os_fkori' => 'ori_clave']);
    }

    /**
     * Gets query for [[Reportes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reportes::class, ['rep_fkori' => 'ori_clave']);
    }
}
