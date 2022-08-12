<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "versiones_app".
 *
 * @property int $id_version
 * @property int|null $num_version
 * @property string|null $link
 * @property string|null $fecha
 */
class VersionApp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'versiones_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num_version'], 'integer'],
            [['fecha'], 'safe'],
            [['link'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_version' => 'Clave',
            'num_version' => 'Versión',
            'link' => 'Enlace',
            'fecha' => 'Fecha',
        ];
    }
}
