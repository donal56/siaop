<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClaseVehicular;

/**
 * ClaseVehicularSearch represents the model behind the search form of `app\models\ClaseVehicular`.
 */
class ClaseVehicularSearch extends ClaseVehicular {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_clase_vehicular', 'id_empresa', 'clase_vehicular', 'descripcion', 'activo', 'fecha_version', 'usuario_version'], 'safe'],
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
        $query = ClaseVehicular::find();

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
            'id_clase_vehicular' => $this->id_clase_vehicular,
            'id_empresa' => $this->id_empresa,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'clase_vehicular', $this->clase_vehicular])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}