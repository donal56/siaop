<?php

namespace app\models;

use Yii;
use app\models\Archivo;
use app\models\OrdenServicio;
use app\models\TipoArchivo;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "ordenes_servicio_archivos".
 *
 * @property int $id_orden_servicio_archivo
 * @property int $id_orden_servicio
 * @property int $id_archivo
 * @property int $id_tipo_archivo
 * @property int $validado
 * @property int|null $usuario_validacion
 * @property string|null $fecha_validacion
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Archivo $archivo
 * @property OrdenServicio $ordenServicio
 * @property TipoArchivo $tipoArchivo
 * @property User $usuario
 * @property User $usuarioVersion
 */
class OrdenServicioArchivo extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ordenes_servicio_archivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_orden_servicio', 'id_archivo', 'id_tipo_archivo'], 'required'],
            [['id_orden_servicio', 'id_archivo', 'id_tipo_archivo', 'validado', 'usuario_validacion'], 'integer'],
            [['fecha_validacion'], 'safe'],
            [['id_archivo'], 'exist', 'skipOnError' => true, 'targetClass' => Archivo::class, 'targetAttribute' => ['id_archivo' => 'id_archivo']],
            [['id_orden_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenServicio::class, 'targetAttribute' => ['id_orden_servicio' => 'id_orden_servicio']],
            [['id_tipo_archivo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoArchivo::class, 'targetAttribute' => ['id_tipo_archivo' => 'id_tipo_archivo']],
            [['usuario_validacion'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_validacion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_orden_servicio_archivo' => 'ID',
            'id_orden_servicio' => 'Orden de servicio',
            'id_archivo' => 'Archivo',
            'id_tipo_archivo' => 'Tipo de archivo',
            'validado' => 'Validado',
            'usuario_validacion' => 'Usuario de validación',
            'fecha_validacion' => 'Fecha de validación',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[Archivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchivo() {
        return $this->hasOne(Archivo::class, ['id_archivo' => 'id_archivo']);
    }

    /**
     * Gets query for [[OrdenServicio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenServicio() {
        return $this->hasOne(OrdenServicio::class, ['id_orden_servicio' => 'id_orden_servicio']);
    }

    /**
     * Gets query for [[TipoArchivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoArchivo() {
        return $this->hasOne(TipoArchivo::class, ['id_tipo_archivo' => 'id_tipo_archivo']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario() {
        return $this->hasOne(User::class, ['id' => 'usuario_validacion']);
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
            OrdenServicioArchivo::find()->orderBy(['id_orden_servicio_archivo' => SORT_ASC])->all(), 
                'id_orden_servicio_archivo', 
                'id_orden_servicio_archivo'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
