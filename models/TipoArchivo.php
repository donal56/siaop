<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use app\models\Proceso;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tipos_archivos".
 *
 * @property int $id_tipo_archivo
 * @property int $id_proceso
 * @property int $id_empresa
 * @property string $tipo_archivo
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property Proceso $proceso
 * @property User $usuarioVersion
 */
class TipoArchivo extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tipos_archivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_proceso', 'tipo_archivo', 'activo'], 'required'],
            [['id_proceso', 'activo'], 'integer'],
            [['tipo_archivo'], 'string', 'max' => 255],
            [['id_proceso'], 'exist', 'skipOnError' => true, 'targetClass' => Proceso::class, 'targetAttribute' => ['id_proceso' => 'id_proceso']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_tipo_archivo' => 'ID',
            'id_proceso' => 'Proceso',
            'id_empresa' => 'Empresa',
            'tipo_archivo' => 'Tipo de archivo',
            'activo' => 'Activo',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
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
     * Gets query for [[Proceso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProceso() {
        return $this->hasOne(Proceso::class, ['id_proceso' => 'id_proceso']);
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
            TipoArchivo::find()->orderBy(['tipo_archivo' => SORT_ASC])->all(), 
                'id_tipo_archivo', 
                'tipo_archivo'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        return parent::beforeSave($insert);
    }
}
