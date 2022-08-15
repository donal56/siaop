<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tipos_archivos".
 *
 * @property int $id_tipo_archivo
 * @property int $id_empresa
 * @property string $tipo_archivo
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property User $usuarioVersion
 */
class TipoArchivo extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

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
            [['tipo_archivo', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['tipo_archivo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_tipo_archivo' => 'ID',
            'id_empresa' => 'Empresa',
            'tipo_archivo' => 'Tipo archivo',
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
