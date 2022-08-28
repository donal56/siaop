<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "marcas".
 *
 * @property int $id_marca
 * @property int $id_empresa
 * @property string $marca
 * @property string|null $descripcion
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property User $usuarioVersion
 */
class Marca extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'marcas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['marca', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['marca'], 'string', 'max' => 255],
            [['descripcion'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_marca' => 'ID',
            'id_empresa' => 'Empresa',
            'marca' => 'Marca',
            'descripcion' => 'Descripción',
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
            Marca::find()->orderBy(['marca' => SORT_ASC])->all(), 
                'id_marca', 
                'marca'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        return parent::beforeSave($insert);
    }
}
