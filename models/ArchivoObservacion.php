<?php

namespace app\models;

use Yii;
use app\models\Archivo;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "archivos_observaciones".
 *
 * @property int $id_archivo_observacion
 * @property int $id_archivo
 * @property string $observacion
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Archivo $archivo
 * @property User $usuarioVersion
 */
class ArchivoObservacion extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'archivos_observaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_archivo', 'observacion'], 'required'],
            [['id_archivo'], 'integer'],
            [['observacion'], 'string'],
            [['id_archivo'], 'exist', 'skipOnError' => true, 'targetClass' => Archivo::class, 'targetAttribute' => ['id_archivo' => 'id_archivo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_archivo_observacion' => 'ID',
            'id_archivo' => 'Archivo',
            'observacion' => 'Observacion',
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
     * Gets query for [[UsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            ArchivoObservacion::find()->orderBy(['id_archivo_observacion' => SORT_ASC])->all(), 
                'id_archivo_observacion', 
                'id_archivo_observacion'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
