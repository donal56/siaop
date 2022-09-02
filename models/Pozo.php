<?php

namespace app\models;

use Yii;
use app\models\Empresa;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "pozos".
 *
 * @property int $id_pozo
 * @property int $id_empresa
 * @property string $pozo
 * @property string|null $ubicacion_descripcion
 * @property string|null $ubicacion_x
 * @property string|null $ubicacion_y
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Empresa $empresa
 * @property User $usuarioVersion
 */
class Pozo extends \yii\db\ActiveRecord {

    public $ubicacion;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'pozos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pozo', 'activo', 'ubicacion'], 'required'],
            [['activo'], 'integer'],
            [['pozo', 'ubicacion_descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_pozo' => 'ID',
            'id_empresa' => 'Empresa',
            'pozo' => 'Pozo',
            'ubicacion_descripcion' => 'Descripción de la ubicación',
            'ubicacion_x' => 'Ubicación en X',
            'ubicacion_y' => 'Ubicación en Y',
            'activo' => 'Activo',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    public function loadUbicacion() {

        if($this->isNewRecord) {
            $coords = explode(",", $this->ubicacion);
            $this->ubicacion_x = $coords[0];
            $this->ubicacion_y = $coords[1];
        }
        else {
            $this->ubicacion = $this->ubicacion_x . "," . $this->ubicacion_y;
        }
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
            Pozo::find()->orderBy(['pozo' => SORT_ASC])->all(), 
                'id_pozo', 
                'pozo'
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        $this->id_empresa = Yii::$app->user->identity->id_empresa;
        return parent::beforeSave($insert);
    }
}
