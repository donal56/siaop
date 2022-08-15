<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FormatoSeccion;

/**
 * FormatoSeccionSearch represents the model behind the search form of `app\models\FormatoSeccion`.
 */
class FormatoSeccionSearch extends FormatoSeccion {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_formato_seccion', 'id_formato', 'orden', 'usuario_version'], 'integer'],
            [['formato_seccion', 'fecha_version'], 'safe'],
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
        $query = FormatoSeccion::find();

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
            'id_formato_seccion' => $this->id_formato_seccion,
            'id_formato' => $this->id_formato,
            'orden' => $this->orden,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'formato_seccion', $this->formato_seccion]);

        return $dataProvider;
    }
}
