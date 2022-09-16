<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdenServicioActividad;

/**
 * OrdenServicioActividadSearch represents the model behind the search form of `app\models\OrdenServicioActividad`.
 */
class OrdenServicioActividadSearch extends OrdenServicioActividad {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_orden_servicio_actividad', 'id_orden_servicio', 'id_actividad', 'realizado', 'usuario_version'], 'integer'],
            [['cantidad'], 'number'],
            [['observacion', 'fecha_version'], 'safe'],
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
    public function search($params, $formName = null) {
        $query = OrdenServicioActividad::find()
            ->joinWith([
              "actividad",
              "ordenServicio",
              "usuarioVersion",
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ordenes_servicio_actividades.id_orden_servicio_actividad' => $this->id_orden_servicio_actividad,
            'ordenes_servicio_actividades.id_orden_servicio' => $this->id_orden_servicio,
            'ordenes_servicio_actividades.id_actividad' => $this->id_actividad,
            'ordenes_servicio_actividades.cantidad' => $this->cantidad,
            'ordenes_servicio_actividades.realizado' => $this->realizado,
            'ordenes_servicio_actividades.fecha_version' => $this->fecha_version,
            'ordenes_servicio_actividades.usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'ordenes_servicio_actividades.observacion', $this->observacion]);

        return $dataProvider;
    }
}
