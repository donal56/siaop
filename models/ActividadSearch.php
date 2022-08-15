<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Actividad;

/**
 * ActividadSearch represents the model behind the search form of `app\models\Actividad`.
 */
class ActividadSearch extends Actividad {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_actividad', 'id_unidad_medida', 'id_empresa', 'activo', 'usuario_version'], 'integer'],
            [['actividad', 'fecha_version'], 'safe'],
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
        $query = Actividad::find();

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
            'id_actividad' => $this->id_actividad,
            'id_unidad_medida' => $this->id_unidad_medida,
            'id_empresa' => $this->id_empresa,
            'activo' => $this->activo,
            'fecha_version' => $this->fecha_version,
            'usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'actividad', $this->actividad]);

        return $dataProvider;
    }
}
