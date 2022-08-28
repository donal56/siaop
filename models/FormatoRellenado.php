<?php

namespace app\models;

use Yii;
use app\models\Formato;
use app\models\RubricaRespuesta;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "formatos_rellenados".
 *
 * @property int $id_formato_rellenado
 * @property int $id_formato
 * @property string $fecha
 * @property string $hora
 * @property string|null $parametro_1
 * @property string|null $parametro_2
 * @property string|null $parametro_3
 * @property string|null $elaborado_por
 * @property string|null $revisado_por
 * @property string|null $autorizador_por
 * @property string|null $observaciones_generales
 * @property int $activo
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Formato $formato
 * @property User $usuarioVersion
 * @property RubricaRespuesta[] $rubricaRespuestas
 */
class FormatoRellenado extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'formatos_rellenados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_formato', 'fecha', 'hora', 'activo'], 'required'],
            [['id_formato', 'activo'], 'integer'],
            [['fecha', 'hora'], 'safe'],
            [['parametro_1', 'parametro_2', 'parametro_3', 'elaborado_por', 'revisado_por', 'autorizador_por'], 'string', 'max' => 255],
            [['observaciones_generales'], 'string', 'max' => 512],
            [['id_formato'], 'exist', 'skipOnError' => true, 'targetClass' => Formato::class, 'targetAttribute' => ['id_formato' => 'id_formato']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_formato_rellenado' => 'ID',
            'id_formato' => 'Formato',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'parametro_1' => 'Parámetro  1',
            'parametro_2' => 'Parámetro  2',
            'parametro_3' => 'Parámetro  3',
            'elaborado_por' => 'Elaborado por',
            'revisado_por' => 'Revisado por',
            'autorizador_por' => 'Autorizador por',
            'observaciones_generales' => 'Observaciones generales',
            'activo' => 'Activo',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[Formato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormato() {
        return $this->hasOne(Formato::class, ['id_formato' => 'id_formato']);
    }

    /**
     * Gets query for [[UsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    /**
     * Gets query for [[RubricaRespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubricaRespuestas() {
        return $this->hasMany(RubricaRespuesta::class, ['id_formato_rellenado' => 'id_formato_rellenado']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            FormatoRellenado::find()->where(['activo' => 1])->orderBy(['' => SORT_ASC])->all(), 
                'id_formato_rellenado', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
