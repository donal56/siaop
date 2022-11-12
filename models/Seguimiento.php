<?php

namespace app\models;

use Yii;
use app\components\validators\LatitudValidator;
use app\components\validators\LongitudValidator;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "seguimientos".
 *
 * @property int $id_seguimiento
 * @property string $fecha
 * @property string $latitud
 * @property string $longitud
 * @property int $id_usuario
 *
 * @property User $usuario
 */
class Seguimiento extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'seguimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fecha'], 'safe'],
            [['latitud', 'longitud'], 'required'],
            [['id_usuario'], 'integer'],
            [['latitud', 'longitud'], 'string', 'max' => 64],
            ['longitud', LongitudValidator::class],
            ['latitud', LatitudValidator::class],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_seguimiento' => 'ID',
            'fecha' => 'Fecha',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'id_usuario' => 'Usuario',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario() {
        return $this->hasOne(User::class, ['id' => 'id_usuario']);
    }

    public function beforeSave($insert) {
        
        if(!$insert) return false;
        
        $this->fecha = date('Y-m-d H:i:s');
        $this->id_usuario = Yii::$app->user->identity->id;

        return parent::beforeSave($insert);
    }
}
