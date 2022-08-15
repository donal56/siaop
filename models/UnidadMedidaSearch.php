<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UnidadMedida;

/**
 * UnidadMedidaSearch represents the model behind the search form of `app\models\UnidadMedida`.
 */
class UnidadMedidaSearch extends UnidadMedida {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_unidad_medida', 'id_empresa', 'activo', 'usuario_version'], 'integer'],
            [['unidad_medida', 'nombre_corto', 'fecha_version'], 'safe'],
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
        $query = UnidadMedida::find();

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
            'id_unidad_medida' => $this->id_unidad_medida,
            'id_empresa' => $this->id_empresa,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'unidad_medida', $this->unidad_medida])
            ->andFilterWhere(['like', 'nombre_corto', $this->nombre_corto]);

        return $dataProvider;
    }
}
