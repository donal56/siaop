<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoUnidadVehicular;

/**
 * TipoUnidadVehicularSearch represents the model behind the search form of `app\models\TipoUnidadVehicular`.
 */
class TipoUnidadVehicularSearch extends TipoUnidadVehicular {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_tipo_unidad_vehicular', 'id_empresa', 'tipo_unidad_vehicular', 'descripcion', 'activo', 'fecha_version', 'usuario_version'], 'safe'],
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
        $query = TipoUnidadVehicular::find();

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
            'id_tipo_unidad_vehicular' => $this->id_tipo_unidad_vehicular,
            'id_empresa' => $this->id_empresa,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'tipo_unidad_vehicular', $this->tipo_unidad_vehicular])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
