<?php

namespace app\models;

use Yii;
use app\models\Formato;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "formatos_secciones".
 *
 * @property int $id_formato_seccion
 * @property int $id_formato
 * @property int $orden
 * @property string $formato_seccion
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property Formato $formato
 * @property User $usuarioVersion
 */
class FormatoSeccion extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'formatos_secciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_formato', 'orden', 'formato_seccion'], 'required'],
            [['id_formato', 'orden'], 'integer'],
            [['formato_seccion'], 'string', 'max' => 255],
            [['id_formato'], 'exist', 'skipOnError' => true, 'targetClass' => Formato::class, 'targetAttribute' => ['id_formato' => 'id_formato']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_formato_seccion' => 'ID',
            'id_formato' => 'Formato',
            'orden' => 'Orden',
            'formato_seccion' => 'Formato seccion',
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

    public static function generateDropdownData() {
        return ArrayHelper::map(
            FormatoSeccion::find()->orderBy(['formato_seccion' => SORT_ASC])->all(), 
            'id_formato_seccion', 
            'formato_seccion'
        );
    }

    public function beforeSave($insert) {
                $this->fecha_version = date('Y-m-d H:i:s');
                        $this->usuario_version = Yii::$app->user->identity->id;
                        return parent::beforeSave($insert);
    }
}
