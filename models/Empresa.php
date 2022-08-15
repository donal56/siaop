<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "empresas".
 *
 * @property int $id_empresa
 * @property string $empresa
 * @property string|null $descripcion
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property User $usuarioVersion
 */
class Empresa extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'empresas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['empresa', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['empresa'], 'string', 'max' => 120],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_empresa' => 'ID',
            'empresa' => 'Empresa',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
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
            Empresa::find()->orderBy(['empresa' => SORT_ASC])->all(), 
            'id_empresa', 
            'empresa'
        );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        return parent::beforeSave($insert);
    }
}
