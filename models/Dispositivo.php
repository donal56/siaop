<?php

namespace app\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "dispositivos".
 *
 * @property int $dis_id
 * @property int|null $dis_user_id
 * @property string|null $dis_token
 * @property string|null $dis_id_empresa
 */
class Dispositivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dispositivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dis_user_id'], 'integer'],
            [['dis_token'], 'string'],
            [['dis_id_empresa'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dis_id' => 'Clave',
            'dis_user_id' => 'Usuario',
            'dis_token' => 'Token',
            'dis_id_empresa' => 'Socio comercial',
        ];
    }

    public function getUsuario() {
        return $this->hasOne(User::class, ['id' => 'dis_user_id']);
    }
}
