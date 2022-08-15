<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use app\models\TipoFormato;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "formatos".
 *
 * @property int $id_formato
 * @property int $id_empresa
 * @property int|null $id_tipo_formato
 * @property string $titulo
 * @property string $fecha_creacion
 * @property string|null $codigo
 * @property string|null $revision
 * @property string|null $subtitulo
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property TipoFormato $tipoFormato
 * @property User $usuarioVersion
 */
class Formato extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'formatos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_tipo_formato', 'activo'], 'integer'],
            [['titulo', 'fecha_creacion', 'activo'], 'required'],
            [['fecha_creacion'], 'safe'],
            [['titulo', 'subtitulo'], 'string', 'max' => 255],
            [['codigo', 'revision'], 'string', 'max' => 50],
            [['id_tipo_formato'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFormato::class, 'targetAttribute' => ['id_tipo_formato' => 'id_tipo_formato']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_formato' => 'ID',
            'id_empresa' => 'Empresa',
            'id_tipo_formato' => 'Tipo formato',
            'titulo' => 'Titulo',
            'fecha_creacion' => 'Fecha creacion',
            'codigo' => 'Codigo',
            'revision' => 'Revision',
            'subtitulo' => 'Subtitulo',
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
     * Gets query for [[TipoFormato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoFormato() {
        return $this->hasOne(TipoFormato::class, ['id_tipo_formato' => 'id_tipo_formato']);
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
            Formato::find()->orderBy(['titulo' => SORT_ASC])->all(), 
            'id_formato', 
            'titulo'
        );
    }

    public function beforeSave($insert) {
                $this->fecha_version = date('Y-m-d H:i:s');
                        $this->usuario_version = Yii::$app->user->identity->id;
                        $this->id_empresa = Yii::$app->user->identity->id_empresa;
                return parent::beforeSave($insert);
    }
}
