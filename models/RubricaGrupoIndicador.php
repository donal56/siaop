<?php

namespace app\models;

use Yii;
use app\models\Rubrica;
use app\models\RubricaIndicador;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "rubricas_grupos_indicadores".
 *
 * @property int $id_rubrica_grupo_indicador
 * @property int $id_rubrica
 * @property string $rubrica_grupo_indicador
 * @property int $orden
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Rubrica $rubrica
 * @property User $usuarioVersion
 * @property RubricaIndicador[] $rubricaIndicadores
 */
class RubricaGrupoIndicador extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'rubricas_grupos_indicadores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_rubrica', 'rubrica_grupo_indicador', 'orden'], 'required'],
            [['id_rubrica', 'orden'], 'integer'],
            [['rubrica_grupo_indicador'], 'string', 'max' => 512],
            [['id_rubrica'], 'exist', 'skipOnError' => true, 'targetClass' => Rubrica::class, 'targetAttribute' => ['id_rubrica' => 'id_rubrica']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_rubrica_grupo_indicador' => 'ID',
            'id_rubrica' => 'Rúbrica',
            'rubrica_grupo_indicador' => 'Grupo de indicadores',
            'orden' => 'Orden',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[Rubrica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubrica() {
        return $this->hasOne(Rubrica::class, ['id_rubrica' => 'id_rubrica']);
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
     * Gets query for [[RubricaIndicadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubricaIndicadores() {
        return $this->hasMany(RubricaIndicador::class, ['id_rubrica_grupo_indicador' => 'id_rubrica_grupo_indicador']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            RubricaGrupoIndicador::find()->orderBy(['' => SORT_ASC])->all(), 
                'id_rubrica_grupo_indicador', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
