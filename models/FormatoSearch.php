<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Formato;

/**
 * FormatoSearch represents the model behind the search form of `app\models\Formato`.
 */
class FormatoSearch extends Formato {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_formato', 'id_empresa', 'id_tipo_formato', 'activo', 'usuario_version'], 'integer'],
            [['titulo', 'fecha_creacion', 'codigo', 'revision', 'subtitulo', 'fecha_version'], 'safe'],
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
        $query = Formato::find();

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
            'id_formato' => $this->id_formato,
            'id_empresa' => $this->id_empresa,
            'id_tipo_formato' => $this->id_tipo_formato,
            'fecha_creacion' => $this->fecha_creacion,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'codigo', $this->codigo])
            ->andFilterWhere(['like', 'revision', $this->revision])
            ->andFilterWhere(['like', 'subtitulo', $this->subtitulo]);

        return $dataProvider;
    }
}
