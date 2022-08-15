<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "unidades_medida".
 *
 * @property int $id_unidad_medida
 * @property int $id_empresa
 * @property string $unidad_medida
 * @property string|null $nombre_corto
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property User $usuarioVersion
 */
class UnidadMedida extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unidades_medida';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['unidad_medida', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['unidad_medida'], 'string', 'max' => 255],
            [['nombre_corto'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_unidad_medida' => 'ID',
            'id_empresa' => 'Empresa',
            'unidad_medida' => 'Unidad medida',
            'nombre_corto' => 'Nombre corto',
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
            UnidadMedida::find()->orderBy(['unidad_medida' => SORT_ASC])->all(), 
            'id_unidad_medida', 
            'unidad_medida'
        );
    }

    public function beforeSave($insert) {
                $this->fecha_version = date('Y-m-d H:i:s');
                        $this->usuario_version = Yii::$app->user->identity->id;
                        $this->id_empresa = Yii::$app->user->identity->id_empresa;
                return parent::beforeSave($insert);
    }
}
