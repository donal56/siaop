<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "dispositivos".
 *
 * @property int $id_dispositivo
 * @property int $usuario
 * @property string $token
 * @property int $activo
 *
 * @property User $usuario
 */
class Dispositivo extends \yii\db\ActiveRecord {

    public function __construct($config = []) {
        $this->activo = 1;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'dispositivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['usuario', 'token'], 'required'],
            [['usuario', 'activo'], 'integer'],
            [['token'], 'string', 'max' => 100],
            [['token'], 'unique'],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_dispositivo' => 'ID',
            'usuario' => 'Usuario',
            'token' => 'Token',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario() {
        return $this->hasOne(User::class, ['id' => 'usuario']);
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            Dispositivo::find()->orderBy(['id_dispositivo' => SORT_ASC])->all(), 
            'id_dispositivo', 
            'id_dispositivo'
        );
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
