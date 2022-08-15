<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ParametroValor;

/**
 * ParametrosValoresSearch represents the model behind the search form of `app\models\ParametroValor`.
 */
class ParametroValorSearch extends ParametroValor {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_parametro_valor', 'activo', 'id_parametro', 'id_empresa', 'usuario_version'], 'integer'],
            [['valor', 'fecha_version'], 'safe'],
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
        $query = ParametroValor::find();

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
            'id_parametro_valor' => $this->id_parametro_valor,
            'activo' => $this->activo,
            'id_parametro' => $this->id_parametro,
            'id_empresa' => $this->id_empresa,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'valor', $this->valor]);

        return $dataProvider;
    }
}
