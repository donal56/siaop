<?php

namespace app\models;

use Yii;
use app\models\FormatoRellenado;
use app\models\RubricaCriterio;
use app\models\RubricaIndicador;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "rubricas_respuestas".
 *
 * @property int $id_rubrica_respuesta
 * @property int $id_rubrica_criterio
 * @property int $id_rubrica_indicador
 * @property int $id_formato_rellenado
 * @property string|null $observacion
 * @property string|null $parametro_1
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property FormatoRellenado $formatoRellenado
 * @property RubricaCriterio $rubricaCriterio
 * @property RubricaIndicador $rubricaIndicador
 * @property User $usuarioVersion
 */
class RubricaRespuesta extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'rubricas_respuestas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_rubrica_criterio', 'id_rubrica_indicador', 'id_formato_rellenado'], 'required'],
            [['id_rubrica_criterio', 'id_rubrica_indicador', 'id_formato_rellenado'], 'integer'],
            [['observacion'], 'string', 'max' => 512],
            [['parametro_1'], 'string', 'max' => 255],
            [['id_formato_rellenado'], 'exist', 'skipOnError' => true, 'targetClass' => FormatoRellenado::class, 'targetAttribute' => ['id_formato_rellenado' => 'id_formato_rellenado']],
            [['id_rubrica_criterio'], 'exist', 'skipOnError' => true, 'targetClass' => RubricaCriterio::class, 'targetAttribute' => ['id_rubrica_criterio' => 'id_rubrica_criterio']],
            [['id_rubrica_indicador'], 'exist', 'skipOnError' => true, 'targetClass' => RubricaIndicador::class, 'targetAttribute' => ['id_rubrica_indicador' => 'id_rubrica_indicador']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_rubrica_respuesta' => 'ID',
            'id_rubrica_criterio' => 'Criterio',
            'id_rubrica_indicador' => 'Indicador',
            'id_formato_rellenado' => 'Formato rellenado',
            'observacion' => 'Observación',
            'parametro_1' => 'Parámetro  1',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[FormatoRellenado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormatoRellenado() {
        return $this->hasOne(FormatoRellenado::class, ['id_formato_rellenado' => 'id_formato_rellenado']);
    }

    /**
     * Gets query for [[RubricaCriterio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubricaCriterio() {
        return $this->hasOne(RubricaCriterio::class, ['id_rubrica_criterio' => 'id_rubrica_criterio']);
    }

    /**
     * Gets query for [[RubricaIndicador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubricaIndicador() {
        return $this->hasOne(RubricaIndicador::class, ['id_rubrica_indicador' => 'id_rubrica_indicador']);
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
            RubricaRespuesta::find()->orderBy(['' => SORT_ASC])->all(), 
                'id_rubrica_respuesta', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
