<?php

namespace app\models;

use Yii;
use app\models\FormatoSeccion;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "rubricas".
 *
 * @property int $id_rubrica
 * @property int $id_formato_seccion
 * @property int $orden
 * @property string $rubrica
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property FormatoSeccion $formatoSeccion
 * @property User $usuarioVersion
 */
class Rubrica extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'rubricas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_formato_seccion', 'orden', 'rubrica'], 'required'],
            [['id_formato_seccion', 'orden'], 'integer'],
            [['rubrica'], 'string', 'max' => 255],
            [['id_formato_seccion'], 'exist', 'skipOnError' => true, 'targetClass' => FormatoSeccion::class, 'targetAttribute' => ['id_formato_seccion' => 'id_formato_seccion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_rubrica' => 'ID',
            'id_formato_seccion' => 'Sección',
            'orden' => 'Orden',
            'rubrica' => 'Rúbrica',
            'fecha_version' => 'Última fecha de modificación',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    /**
     * Gets query for [[FormatoSeccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormatoSeccion() {
        return $this->hasOne(FormatoSeccion::class, ['id_formato_seccion' => 'id_formato_seccion']);
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
            Rubrica::find()->orderBy(['rubrica' => SORT_ASC])->all(), 
            'id_rubrica', 
            'rubrica'
        );
    }

    public function beforeSave($insert) {
                $this->fecha_version = date('Y-m-d H:i:s');
                        $this->usuario_version = Yii::$app->user->identity->id;
                        return parent::beforeSave($insert);
    }
}
