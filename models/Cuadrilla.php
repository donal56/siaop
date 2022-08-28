<?php

namespace app\models;

use Yii;
use app\models\OrdenServicio;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "cuadrillas".
 *
 * @property int $id_cuadrilla
 * @property int $id_orden_servicio
 * @property int $usuario
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property OrdenServicio $ordenServicio
 * @property User $usuario
 * @property User $usuarioVersion
 */
class Cuadrilla extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'cuadrillas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_orden_servicio', 'usuario'], 'required'],
            [['id_orden_servicio', 'usuario'], 'integer'],
            [['id_orden_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenServicio::class, 'targetAttribute' => ['id_orden_servicio' => 'id_orden_servicio']],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_cuadrilla' => 'ID',
            'id_orden_servicio' => 'Orden de servicio',
            'usuario' => 'Usuario',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
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
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario() {
        return $this->hasOne(User::class, ['id' => 'usuario']);
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
            Cuadrilla::find()->orderBy(['usuario' => SORT_ASC])->all(), 
                'id_cuadrilla', 
                'usuario'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
