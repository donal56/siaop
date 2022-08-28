<?php

namespace app\models;

use Yii;
use app\models\Actividad;
use app\models\OrdenServicio;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "ordenes_servicio_actividades".
 *
 * @property int $id_orden_servicio_actividad
 * @property int $id_orden_servicio
 * @property int $id_actividad
 * @property float $cantidad
 * @property int $realizado
 * @property string|null $observacion
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Actividad $actividad
 * @property OrdenServicio $ordenServicio
 * @property User $usuarioVersion
 */
class OrdenServicioActividad extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ordenes_servicio_actividades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_orden_servicio', 'id_actividad'], 'required'],
            [['id_orden_servicio', 'id_actividad', 'realizado'], 'integer'],
            [['cantidad'], 'number'],
            [['observacion'], 'string', 'max' => 512],
            [['id_orden_servicio', 'id_actividad'], 'unique', 'targetAttribute' => ['id_orden_servicio', 'id_actividad']],
            [['id_actividad'], 'exist', 'skipOnError' => true, 'targetClass' => Actividad::class, 'targetAttribute' => ['id_actividad' => 'id_actividad']],
            [['id_orden_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenServicio::class, 'targetAttribute' => ['id_orden_servicio' => 'id_orden_servicio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_orden_servicio_actividad' => 'ID',
            'id_orden_servicio' => 'Orden de servicio',
            'id_actividad' => 'Actividad',
            'cantidad' => 'Cantidad',
            'realizado' => 'Realizado',
            'observacion' => 'Observación',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[Actividad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividad() {
        return $this->hasOne(Actividad::class, ['id_actividad' => 'id_actividad']);
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
     * Gets query for [[UsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            OrdenServicioActividad::find()->orderBy(['id_orden_servicio_actividad' => SORT_ASC])->all(), 
                'id_orden_servicio_actividad', 
                'id_orden_servicio_actividad'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
