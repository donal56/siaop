<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "clientes".
 *
 * @property int $id_cliente
 * @property int $id_empresa
 * @property string $razon_social
 * @property string|null $rfc
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property User $usuarioVersion
 */
class Cliente extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['razon_social', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['razon_social'], 'string', 'max' => 255],
            [['rfc'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_cliente' => 'ID',
            'id_empresa' => 'Empresa',
            'razon_social' => 'Razon social',
            'rfc' => 'Rfc',
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
            Cliente::find()->orderBy(['razon_social' => SORT_ASC])->all(), 
            'id_cliente', 
            'razon_social'
        );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        return parent::beforeSave($insert);
    }
}
