<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "archivos".
 *
 * @property int $id_archivo
 * @property string $nombre
 * @property string $extension
 * @property string $mime
 * @property int $tamanio
 * @property string $md5
 * @property string $ip
 * @property string $ubicacion_x
 * @property string $ubicacion_y
 * @property string|null $observacion
 * @property string $fecha_carga
 * @property int $usuario_carga
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property User $usuario
 */
class Archivo extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'archivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nombre', 'extension', 'mime', 'tamanio', 'md5', 'ip', 'ubicacion_x', 'ubicacion_y', 'fecha_carga', 'usuario_carga'], 'required'],
            [['tamanio', 'usuario_carga'], 'integer'],
            [['fecha_carga'], 'safe'],
            [['nombre', 'mime'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 4],
            [['md5'], 'string', 'max' => 32],
            [['ip', 'ubicacion_x', 'ubicacion_y'], 'string', 'max' => 64],
            [['observacion'], 'string', 'max' => 512],
            [['md5'], 'unique'],
            [['usuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_carga' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_archivo' => 'ID',
            'nombre' => 'Nombre',
            'extension' => 'Extensión',
            'mime' => 'Mime',
            'tamanio' => 'Tamaño',
            'md5' => 'MD5',
            'ip' => 'IP',
            'ubicacion_x' => 'Ubicación en X',
            'ubicacion_y' => 'Ubicación en Y',
            'observacion' => 'Observación',
            'fecha_carga' => 'Fecha de carga',
            'usuario_carga' => 'Usuario de carga',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario() {
        return $this->hasOne(User::class, ['id' => 'usuario_carga']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            Archivo::find()->orderBy(['nombre' => SORT_ASC])->all(), 
            'id_archivo', 
            'nombre'
        );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
