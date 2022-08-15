<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UnidadVehicular;

/**
 * UnidadVehicularSearch represents the model behind the search form of `app\models\UnidadVehicular`.
 */
class UnidadVehicularSearch extends UnidadVehicular {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_unidad_vehicular', 'id_empresa', 'id_marca', 'id_tipo_unidad_vehicular', 'id_clase_vehicular', 'id_tipo_combustible', 'activo', 'usuario_version'], 'integer'],
            [['modelo', 'placa', 'motor', 'tarjeta_circulacion', 'numero_identificacion_vehicular', 'poliza', 'vigencia_poliza', 'permiso_ruta_sct', 'numero_economica', 'permiso_trp', 'vigencia_trp', 'permiso_trme', 'vigencia_trme', 'fecha_version'], 'safe'],
            [['rendimiento_combustible'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = UnidadVehicular::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_unidad_vehicular' => $this->id_unidad_vehicular,
            'id_empresa' => $this->id_empresa,
            'id_marca' => $this->id_marca,
            'id_tipo_unidad_vehicular' => $this->id_tipo_unidad_vehicular,
            'id_clase_vehicular' => $this->id_clase_vehicular,
            'id_tipo_combustible' => $this->id_tipo_combustible,
            'vigencia_poliza' => $this->vigencia_poliza,
            'vigencia_trp' => $this->vigencia_trp,
            'vigencia_trme' => $this->vigencia_trme,
            'rendimiento_combustible' => $this->rendimiento_combustible,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'placa', $this->placa])
            ->andFilterWhere(['like', 'motor', $this->motor])
            ->andFilterWhere(['like', 'tarjeta_circulacion', $this->tarjeta_circulacion])
            ->andFilterWhere(['like', 'numero_identificacion_vehicular', $this->numero_identificacion_vehicular])
            ->andFilterWhere(['like', 'poliza', $this->poliza])
            ->andFilterWhere(['like', 'permiso_ruta_sct', $this->permiso_ruta_sct])
            ->andFilterWhere(['like', 'numero_economica', $this->numero_economica])
            ->andFilterWhere(['like', 'permiso_trp', $this->permiso_trp])
            ->andFilterWhere(['like', 'permiso_trme', $this->permiso_trme]);

        return $dataProvider;
    }
}
