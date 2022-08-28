<?php

namespace app\models;

use Yii;
use app\models\RubricaGrupoIndicador;
use app\models\RubricaRespuesta;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "rubricas_indicadores".
 *
 * @property int $id_rubrica_indicador
 * @property int $id_rubrica_grupo_indicador
 * @property string $rubrica_indicador
 * @property int $orden
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property RubricaGrupoIndicador $rubricaGrupoIndicador
 * @property User $usuarioVersion
 * @property RubricaRespuesta[] $rubricaRespuestas
 */
class RubricaIndicador extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'rubricas_indicadores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_rubrica_grupo_indicador', 'rubrica_indicador', 'orden'], 'required'],
            [['id_rubrica_grupo_indicador', 'orden'], 'integer'],
            [['rubrica_indicador'], 'string', 'max' => 255],
            [['id_rubrica_grupo_indicador'], 'exist', 'skipOnError' => true, 'targetClass' => RubricaGrupoIndicador::class, 'targetAttribute' => ['id_rubrica_grupo_indicador' => 'id_rubrica_grupo_indicador']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_rubrica_indicador' => 'ID',
            'id_rubrica_grupo_indicador' => 'Grupo de indicadores',
            'rubrica_indicador' => 'Indicador',
            'orden' => 'Orden',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[RubricaGrupoIndicador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubricaGrupoIndicador() {
        return $this->hasOne(RubricaGrupoIndicador::class, ['id_rubrica_grupo_indicador' => 'id_rubrica_grupo_indicador']);
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
        return $this->hasMany(RubricaRespuesta::class, ['id_rubrica_indicador' => 'id_rubrica_indicador']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            RubricaIndicador::find()->orderBy(['' => SORT_ASC])->all(), 
                'id_rubrica_indicador', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
