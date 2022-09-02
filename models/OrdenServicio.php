<?php

namespace app\models;

use Yii;
use app\models\Cuadrilla;
use app\models\Cliente;
use app\models\Empresa;
use app\models\Estatus;
use app\models\Pozo;
use app\models\TipoOrdenServicio;
use app\models\UnidadVehicular;
use app\models\Origen;
use app\models\OrdenServicioActividad;
use app\models\Actividad;
use app\models\OrdenServicioArchivo;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "ordenes_servicio".
 *
 * @property int $id_orden_servicio
 * @property int $id_empresa
 * @property int $id_tipo_orden_servicio
 * @property int $id_cliente
 * @property int $id_estatus
 * @property int|null $id_unidad_vehicular
 * @property int|null $id_pozo
 * @property int|null $usuario_jefe_cuadrilla
 * @property int|null $usuario_cliente_solicitante
 * @property string|null $hora_salida
 * @property int|null $distancia_kms
 * @property float|null $combustible_aproximado_lts
 * @property string $ruta_descripcion
 * @property string $fecha
 * @property string $hora_entrada
 * @property string $origen_x
 * @property string $origen_y
 * @property string $destino_x
 * @property string $destino_y
 * @property string|null $fecha_hora_llegada_real
 * @property string|null $fecha_hora_salida_real
 * @property string|null $fecha_hora_inicio_trabajo
 * @property string|null $fecha_hora_final_trabajo
 * @property string $fecha_captura
 * @property int $usuario_captura
 * @property int $origen_version
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Cuadrilla[] $cuadrillas
 * @property Cliente $cliente
 * @property Empresa $empresa
 * @property Estatus $estatus
 * @property Pozo $pozo
 * @property TipoOrdenServicio $tipoOrdenServicio
 * @property UnidadVehicular $unidadVehicular
 * @property Origen $origenVersion
 * @property User $usuarioCaptura
 * @property User $usuarioClienteSolicitante
 * @property User $usuarioJefeCuadrilla
 * @property User $usuarioVersion
 * @property OrdenServicioActividad[] $ordenServicioActividades
 * @property Actividad[] $actividades
 * @property OrdenServicioArchivo[] $ordenServicioArchivos
 */
class OrdenServicio extends \yii\db\ActiveRecord {

    public $origen, $destino;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ordenes_servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_tipo_orden_servicio', 'id_cliente', 'id_estatus', 'ruta_descripcion', 'fecha', 'hora_entrada', 'origen', 'destino'], 'required'],
            [['id_tipo_orden_servicio', 'id_cliente', 'id_estatus', 'id_unidad_vehicular', 'id_pozo', 'usuario_jefe_cuadrilla', 'usuario_cliente_solicitante', 'distancia_kms'], 'integer'],
            [['hora_salida', 'fecha', 'hora_entrada', 'fecha_hora_llegada_real', 'fecha_hora_salida_real', 'fecha_hora_inicio_trabajo', 'fecha_hora_final_trabajo'], 'safe'],
            [['combustible_aproximado_lts'], 'number'],
            [['ruta_descripcion'], 'string', 'max' => 255],
            [['origen_x', 'origen_y', 'destino_x', 'destino_y'], 'string', 'max' => 64],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::class, 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_estatus'], 'exist', 'skipOnError' => true, 'targetClass' => Estatus::class, 'targetAttribute' => ['id_estatus' => 'id_estatus']],
            [['id_pozo'], 'exist', 'skipOnError' => true, 'targetClass' => Pozo::class, 'targetAttribute' => ['id_pozo' => 'id_pozo']],
            [['id_tipo_orden_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => TipoOrdenServicio::class, 'targetAttribute' => ['id_tipo_orden_servicio' => 'id_tipo_orden_servicio']],
            [['id_unidad_vehicular'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadVehicular::class, 'targetAttribute' => ['id_unidad_vehicular' => 'id_unidad_vehicular']],
            [['usuario_cliente_solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_cliente_solicitante' => 'id']],
            [['usuario_jefe_cuadrilla'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_jefe_cuadrilla' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_orden_servicio' => 'ID',
            'id_empresa' => 'Empresa',
            'id_tipo_orden_servicio' => 'Tipo de orden de servicio',
            'id_cliente' => 'Cliente',
            'id_estatus' => 'Estatus',
            'id_unidad_vehicular' => 'Unidad vehicular',
            'id_pozo' => 'Pozo',
            'usuario_jefe_cuadrilla' => 'Jefe de cuadrilla',
            'usuario_cliente_solicitante' => 'Cliente solicitante',
            'hora_salida' => 'Hora de salida',
            'distancia_kms' => 'Distancia (Kms)',
            'combustible_aproximado_lts' => 'Combustible aproximado (Lts)',
            'ruta_descripcion' => 'Descripción de ruta',
            'fecha' => 'Fecha',
            'hora_entrada' => 'Hora de entrada',
            'origen_x' => 'Origen en X',
            'origen_y' => 'Origen en Y',
            'destino_x' => 'Destino en X',
            'destino_y' => 'Destino en Y',
            'fecha_hora_llegada_real' => 'Fecha de llegada registrada',
            'fecha_hora_salida_real' => 'Fecha de Salida registrada',
            'fecha_hora_inicio_trabajo' => 'Fecha de inicio de trabajo',
            'fecha_hora_final_trabajo' => 'Fecha de final  de trabajo',
            'fecha_captura' => 'Fecha de captura',
            'usuario_captura' => 'Usuario de captura',
            'origen_version' => 'Origen',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    public function loadOrigen() {

        if(isset($this->origen)) {
            $coords = explode(",", $this->origen);
            $this->origen_x = $coords[0];
            $this->origen_y = $coords[1];
        }
        else {
            $this->origen = $this->origen_x . "," . $this->origen_y;
        }
    }

    public function loadDestino() {

        if(isset($this->destino)) {
            $coords = explode(",", $this->destino);
            $this->destino_x = $coords[0];
            $this->destino_y = $coords[1];
        }
        else {
            $this->destino = $this->destino_x . "," . $this->destino_y;
        }
    }

    /**
     * Gets query for [[Cuadrillas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuadrillas() {
        return $this->hasMany(Cuadrilla::class, ['id_orden_servicio' => 'id_orden_servicio']);
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente() {
        return $this->hasOne(Cliente::class, ['id_cliente' => 'id_cliente']);
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
     * Gets query for [[Estatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstatus() {
        return $this->hasOne(Estatus::class, ['id_estatus' => 'id_estatus']);
    }

    /**
     * Gets query for [[Pozo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPozo() {
        return $this->hasOne(Pozo::class, ['id_pozo' => 'id_pozo']);
    }

    /**
     * Gets query for [[TipoOrdenServicio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoOrdenServicio() {
        return $this->hasOne(TipoOrdenServicio::class, ['id_tipo_orden_servicio' => 'id_tipo_orden_servicio']);
    }

    /**
     * Gets query for [[UnidadVehicular]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadVehicular() {
        return $this->hasOne(UnidadVehicular::class, ['id_unidad_vehicular' => 'id_unidad_vehicular']);
    }

    /**
     * Gets query for [[OrigenVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrigenVersion() {
        return $this->hasOne(Origen::class, ['id_origen' => 'origen_version']);
    }

    /**
     * Gets query for [[UsuarioCaptura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCaptura() {
        return $this->hasOne(User::class, ['id' => 'usuario_captura']);
    }

    /**
     * Gets query for [[UsuarioClienteSolicitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioClienteSolicitante() {
        return $this->hasOne(User::class, ['id' => 'usuario_cliente_solicitante']);
    }

    /**
     * Gets query for [[UsuarioJefeCuadrilla]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioJefeCuadrilla() {
        return $this->hasOne(User::class, ['id' => 'usuario_jefe_cuadrilla']);
    }

    /**
     * Gets query for [[UsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    /**
     * Gets query for [[OrdenServicioActividades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenServicioActividades() {
        return $this->hasMany(OrdenServicioActividad::class, ['id_orden_servicio' => 'id_orden_servicio']);
    }

    /**
     * Gets query for [[Actividades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividades() {
        return $this->hasMany(Actividad::class, ['id_actividad' => 'id_actividad'])
                   ->viaTable('ordenes_servicio_actividades', ['id_orden_servicio' => 'id_orden_servicio']);
    }

    /**
     * Gets query for [[OrdenServicioArchivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenServicioArchivos() {
        return $this->hasMany(OrdenServicioArchivo::class, ['id_orden_servicio' => 'id_orden_servicio']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            OrdenServicio::find()->orderBy(['id_orden_servicio' => SORT_ASC])->all(), 
                'id_orden_servicio', 
                'id_orden_servicio'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->origen_version = ORIGEN::WEB;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        
        if($insert) {
            $this->fecha_captura = date('Y-m-d H:i:s');
            $this->usuario_captura = Yii::$app->user->identity->id;
        }

        return parent::beforeSave($insert);
    }
}
