<?php

namespace app\models;

use Yii;
use app\models\Rubrica;
use app\models\RubricaRespuesta;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "rubricas_criterios".
 *
 * @property int $id_rubrica_criterio
 * @property int $id_rubrica
 * @property int $orden
 * @property string $rubrica_criterio
 * @property string|null $numero
 * @property string $nota
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Rubrica $rubrica
 * @property User $usuarioVersion
 * @property RubricaRespuesta[] $rubricaRespuestas
 */
class RubricaCriterio extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'rubricas_criterios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_rubrica', 'orden', 'rubrica_criterio', 'nota'], 'required'],
            [['id_rubrica', 'orden'], 'integer'],
            [['rubrica_criterio', 'nota'], 'string', 'max' => 512],
            [['numero'], 'string', 'max' => 64],
            [['id_rubrica'], 'exist', 'skipOnError' => true, 'targetClass' => Rubrica::class, 'targetAttribute' => ['id_rubrica' => 'id_rubrica']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_rubrica_criterio' => 'ID',
            'id_rubrica' => 'Rúbrica',
            'orden' => 'Orden',
            'rubrica_criterio' => 'Criterio',
            'numero' => 'Número',
            'nota' => 'Nota',
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
     * Gets query for [[RubricaRespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRubricaRespuestas() {
        return $this->hasMany(RubricaRespuesta::class, ['id_rubrica_criterio' => 'id_rubrica_criterio']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            RubricaCriterio::find()->orderBy(['' => SORT_ASC])->all(), 
                'id_rubrica_criterio', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
