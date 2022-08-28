<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bitacoras".
 *
 * @property int $id_bitacora
 * @property string $tabla
 * @property string|null $llave_primaria
 * @property string|null $registro_viejo
 * @property string|null $registro_nuevo
 * @property string $accion
 * @property string $fecha_version
 * @property string $dml_created_by
 * @property int|null $usuario_version
 */
class Bitacora extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'bitacoras';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tabla', 'accion', 'dml_created_by'], 'required'],
            [['registro_viejo', 'registro_nuevo'], 'safe'],
            [['accion'], 'string'],
            [['tabla', 'llave_primaria', 'dml_created_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_bitacora' => 'ID',
            'tabla' => 'Tabla',
            'llave_primaria' => 'Llave primaria',
            'registro_viejo' => 'Registro viejo',
            'registro_nuevo' => 'Registro nuevo',
            'accion' => 'Acción',
            'fecha_version' => 'Última fecha de modificación',
            'dml_created_by' => 'Dml Created By',
            'usuario_version' => 'Último usuario de modificación',
        ];
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            Bitacora::find()->orderBy(['' => SORT_ASC])->all(), 
                'id_bitacora', 
                ''
            );
    }

    public function beforeSave($insert) {
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->usuario_version = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
