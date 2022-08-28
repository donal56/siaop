<?php

namespace app\models;

use Yii;
use app\models\OrdenServicio;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tipos_ordenes_servicio".
 *
 * @property int $id_tipo_orden_servicio
 * @property string $tipo_orden_servicio
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property OrdenServicio[] $ordenServicios
 * @property User $usuarioVersion
 */
class TipoOrdenServicio extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tipos_ordenes_servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tipo_orden_servicio', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['tipo_orden_servicio'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_tipo_orden_servicio' => 'ID',
            'tipo_orden_servicio' => 'Tipo de orden de servicio',
            'activo' => 'Activo',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[OrdenServicios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenServicios() {
        return $this->hasMany(OrdenServicio::class, ['id_tipo_orden_servicio' => 'id_tipo_orden_servicio']);
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
            TipoOrdenServicio::find()->where(['activo' => 1])->orderBy(['tipo_orden_servicio' => SORT_ASC])->all(), 
                'id_tipo_orden_servicio', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
