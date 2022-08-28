<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "versiones_app".
 *
 * @property int $id_version_app
 * @property int|null $version_mayor
 * @property int|null $version_menor
 * @property string|null $url
 * @property string|null $fecha
 */
class VersionApp extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'versiones_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['version_mayor', 'version_menor'], 'integer'],
            [['fecha'], 'safe'],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_version_app' => 'ID',
            'version_mayor' => 'Version mayor',
            'version_menor' => 'Version menor',
            'url' => 'URL',
            'fecha' => 'Fecha',
        ];
    }

    public static function generateDropdownData() {
        return ArrayHelper::map(
            VersionApp::find()->orderBy(['' => SORT_ASC])->all(), 
                'id_version_app', 
                ''
            );
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
