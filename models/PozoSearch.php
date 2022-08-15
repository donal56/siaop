<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pozo;

/**
 * PozoSearch represents the model behind the search form of `app\models\Pozo`.
 */
class PozoSearch extends Pozo {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_pozo', 'id_empresa', 'pozo', 'ubicacion_descripcion', 'ubicacion_x', 'ubicacion_y', 'activo', 'fecha_version', 'usuario_version'], 'safe'],
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
        $query = Pozo::find();

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
            'id_pozo' => $this->id_pozo,
            'id_empresa' => $this->id_empresa,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'pozo', $this->pozo])
            ->andFilterWhere(['like', 'ubicacion_descripcion', $this->ubicacion_descripcion])
            ->andFilterWhere(['like', 'ubicacion_x', $this->ubicacion_x])
            ->andFilterWhere(['like', 'ubicacion_y', $this->ubicacion_y]);

        return $dataProvider;
    }
}
