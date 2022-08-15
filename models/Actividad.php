<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use app\models\UnidadMedida;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "actividades".
 *
 * @property int $id_actividad
 * @property int $id_unidad_medida
 * @property int $id_empresa
 * @property string $actividad
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property UnidadMedida $unidadMedida
 * @property User $usuarioVersion
 */
class Actividad extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'actividades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_unidad_medida', 'actividad', 'activo'], 'required'],
            [['id_unidad_medida', 'activo'], 'integer'],
            [['actividad'], 'string', 'max' => 512],
            [['id_unidad_medida'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadMedida::class, 'targetAttribute' => ['id_unidad_medida' => 'id_unidad_medida']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_actividad' => 'ID',
            'id_unidad_medida' => 'Unidad medida',
            'id_empresa' => 'Empresa',
            'actividad' => 'Actividad',
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
     * Gets query for [[UnidadMedida]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadMedida() {
        return $this->hasOne(UnidadMedida::class, ['id_unidad_medida' => 'id_unidad_medida']);
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
            Actividad::find()->orderBy(['actividad' => SORT_ASC])->all(), 
            'id_actividad', 
            'actividad'
        );
    }

    public function beforeSave($insert) {
                $this->fecha_version = date('Y-m-d H:i:s');
                        $this->usuario_version = Yii::$app->user->identity->id;
                        $this->id_empresa = Yii::$app->user->identity->id_empresa;
                return parent::beforeSave($insert);
    }
}
