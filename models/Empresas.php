<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "empresas".
 *
 * @property int $emp_clave
 * @property string $emp_nombre
 * @property string|null $emp_descripcion
 * @property int $emp_activo
 * @property string $emp_codigo
 * @property string $emp_fechaVersion
 * @property int $emp_usuarioVersion
 *
 * @property Actividades[] $actividades
 * @property Centros[] $centros
 * @property User $empUsuarioVersion
 * @property Paquetes[] $paquetes
 */
class Empresas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_nombre', 'emp_codigo', 'emp_activo'], 'required'],
            [['emp_codigo'], 'unique'],
            [['emp_activo'], 'integer'],
            [['emp_nombre'], 'string', 'max' => 120],
            [['emp_codigo'], 'string', 'max' => 20],
            [['emp_descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'emp_clave' => 'Clave',
            'emp_nombre' => ' Nombre',
            'emp_codigo' => 'Código',
            'emp_descripcion' => 'Descripción',
            'emp_activo' => 'Activo',
            'emp_fechaVersion' => 'Fecha-Versión',
            'emp_usuarioVersion' => 'Usuario-Versión',
        ];
    }

    /**
     * Gets query for [[Actividades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividades()
    {
        return $this->hasMany(Actividades::class, ['act_fkemp' => 'emp_clave']);
    }

    /**
     * Gets query for [[Centros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCentros()
    {
        return $this->hasMany(Centros::class, ['cen_fkemp' => 'emp_clave']);
    }

    /**
     * Gets query for [[EmpUsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpUsuarioVersion()
    {
        return $this->hasOne(User::class, ['id' => 'emp_usuarioVersion']);
    }

    /**
     * Gets query for [[Paquetes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaquetes()
    {
        return $this->hasMany(Paquetes::class, ['paq_fkemp' => 'emp_clave']);
    }

    /**
     * Genera las opciones de un dropdown de acuerdo a las capacidades/permisos del usuario
     * Si el usuario tiene permisos mayores, las opciones se agrupan en optgroups
     */
    public static function generateOptGroupDrodDown() {

        $empresas = Empresas::find()
            ->orderBy([
                'emp_nombre' => SORT_ASC,
            ]);

        return ArrayHelper::map($empresas->all(), 'emp_clave', 'emp_nombre');
    }

    public function beforeSave($insert) {
        $this->emp_fechaVersion     =   date('Y-m-d H:i:s');
        $this->emp_usuarioVersion   =   Yii::$app->user->identity->id;

        return parent::beforeSave($insert);
    }
}
