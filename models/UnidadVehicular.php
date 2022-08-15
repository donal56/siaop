<?php

namespace app\models;

use Yii;
use app\models\ClaseVehicular;
use app\models\Empresa;
use app\models\TipoCombustible;
use app\models\Marca;
use app\models\TipoUnidadVehicular;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "unidades_vehiculares".
 *
 * @property int $id_unidad_vehicular
 * @property int $id_empresa
 * @property int $id_marca
 * @property int $id_tipo_unidad_vehicular
 * @property int|null $id_clase_vehicular
 * @property int|null $id_tipo_combustible
 * @property string $modelo
 * @property string $placa
 * @property string|null $motor
 * @property string|null $tarjeta_circulacion
 * @property string|null $numero_identificacion_vehicular
 * @property string|null $poliza
 * @property string|null $vigencia_poliza
 * @property string|null $permiso_ruta_sct Secretaria de caminos y transporte
 * @property string|null $numero_economica
 * @property string|null $permiso_trp Permiso de para transporte de residuos peligrosos
 * @property string|null $vigencia_trp
 * @property string|null $permiso_trme Permiso para transporte de residuos y manejo especial
 * @property string|null $vigencia_trme
 * @property float|null $rendimiento_combustible Kilometros por litro de combustible
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property ClaseVehicular $claseVehicular
 * @property Empresa $empresa
 * @property TipoCombustible $tipoCombustible
 * @property Marca $marca
 * @property TipoUnidadVehicular $tipoUnidadVehicular
 * @property User $usuarioVersion
 */
class UnidadVehicular extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unidades_vehiculares';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_marca', 'id_tipo_unidad_vehicular', 'modelo', 'placa', 'activo'], 'required'],
            [['id_marca', 'id_tipo_unidad_vehicular', 'id_clase_vehicular', 'id_tipo_combustible', 'activo'], 'integer'],
            [['vigencia_poliza', 'vigencia_trp', 'vigencia_trme'], 'safe'],
            [['rendimiento_combustible'], 'number'],
            [['modelo', 'motor'], 'string', 'max' => 100],
            [['placa'], 'string', 'max' => 10],
            [['tarjeta_circulacion', 'numero_identificacion_vehicular', 'poliza', 'permiso_ruta_sct', 'numero_economica', 'permiso_trp', 'permiso_trme'], 'string', 'max' => 32],
            [['id_clase_vehicular'], 'exist', 'skipOnError' => true, 'targetClass' => ClaseVehicular::class, 'targetAttribute' => ['id_clase_vehicular' => 'id_clase_vehicular']],
            [['id_tipo_combustible'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCombustible::class, 'targetAttribute' => ['id_tipo_combustible' => 'id_tipo_combustible']],
            [['id_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::class, 'targetAttribute' => ['id_marca' => 'id_marca']],
            [['id_tipo_unidad_vehicular'], 'exist', 'skipOnError' => true, 'targetClass' => TipoUnidadVehicular::class, 'targetAttribute' => ['id_tipo_unidad_vehicular' => 'id_tipo_unidad_vehicular']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_unidad_vehicular' => 'ID',
            'id_empresa' => 'Empresa',
            'id_marca' => 'Marca',
            'id_tipo_unidad_vehicular' => 'Tipo unidad vehicular',
            'id_clase_vehicular' => 'Clase vehicular',
            'id_tipo_combustible' => 'Tipo combustible',
            'modelo' => 'Modelo',
            'placa' => 'Placa',
            'motor' => 'Motor',
            'tarjeta_circulacion' => 'Tarjeta circulacion',
            'numero_identificacion_vehicular' => 'Numero identificacion vehicular',
            'poliza' => 'Poliza',
            'vigencia_poliza' => 'Vigencia poliza',
            'permiso_ruta_sct' => 'Permiso ruta sct',
            'numero_economica' => 'Numero economica',
            'permiso_trp' => 'Permiso trp',
            'vigencia_trp' => 'Vigencia trp',
            'permiso_trme' => 'Permiso trme',
            'vigencia_trme' => 'Vigencia trme',
            'rendimiento_combustible' => 'Rendimiento combustible',
            'activo' => 'Activo',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[ClaseVehicular]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClaseVehicular() {
        return $this->hasOne(ClaseVehicular::class, ['id_clase_vehicular' => 'id_clase_vehicular']);
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa() {
        return $this->hasOne(Empresa::class, ['id_empresa' => 'id_empresa']);
    }

    /**
     * Gets query for [[TipoCombustible]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoCombustible() {
        return $this->hasOne(TipoCombustible::class, ['id_tipo_combustible' => 'id_tipo_combustible']);
    }

    /**
     * Gets query for [[Marca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarca() {
        return $this->hasOne(Marca::class, ['id_marca' => 'id_marca']);
    }

    /**
     * Gets query for [[TipoUnidadVehicular]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoUnidadVehicular() {
        return $this->hasOne(TipoUnidadVehicular::class, ['id_tipo_unidad_vehicular' => 'id_tipo_unidad_vehicular']);
    }

    /**
     * Gets query for [[UsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            UnidadVehicular::find()->orderBy(['placa' => SORT_ASC])->all(), 
            'id_unidad_vehicular', 
            'placa'
        );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        return parent::beforeSave($insert);
    }
}
