<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "origenes".
 *
 * @property int $id_origen
 * @property string $origen
 */
class Origen extends \yii\db\ActiveRecord {

    const WEB = 1;
    const MOVIL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'origenes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_origen', 'origen'], 'required'],
            [['id_origen'], 'integer'],
            [['origen'], 'string', 'max' => 100],
            [['id_origen'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_origen' => 'ID',
            'origen' => 'Origen',
        ];
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            Origen::find()->orderBy(['nombre' => SORT_ASC])->all(), 
            'id_origen', 
            'nombre'
        );
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
