<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tipos_combustibles".
 *
 * @property int $id_tipo_combustible
 * @property int $id_empresa
 * @property string $tipo_combustible
 * @property string|null $descripcion
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property User $usuarioVersion
 */
class TipoCombustible extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tipos_combustibles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tipo_combustible', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['tipo_combustible'], 'string', 'max' => 255],
            [['descripcion'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_tipo_combustible' => 'ID',
            'id_empresa' => 'Empresa',
            'tipo_combustible' => 'Tipo combustible',
            'descripcion' => 'Descripcion',
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
            TipoCombustible::find()->orderBy(['tipo_combustible' => SORT_ASC])->all(), 
            'id_tipo_combustible', 
            'tipo_combustible'
        );
    }

    public function beforeSave($insert) {
                $this->fecha_version = date('Y-m-d H:i:s');
                        $this->usuario_version = Yii::$app->user->identity->id;
                        $this->id_empresa = Yii::$app->user->identity->id_empresa;
                return parent::beforeSave($insert);
    }
}
