<?php

namespace app\models;

/**
 * This is the model class for table "cron_executions".
 *
 * @property int $ce_clave
 * @property string $ce_cron
 * @property string $ce_fecha
 * @property int $ce_estado
 * @property string|null $ce_salida
 */
class CronExecutions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_executions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ce_cron', 'ce_estado'], 'required'],
            [['ce_fecha'], 'safe'],
            [['ce_estado'], 'integer'],
            [['ce_cron'], 'string', 'max' => 255],
            [['ce_salida'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ce_clave' => 'Clave',
            'ce_cron' => 'Nombre del cronjob',
            'ce_fecha' => 'Fecha de ejecución',
            'ce_estado' => 'Estado',
            'ce_salida' => 'Salida',
        ];
    }
}
