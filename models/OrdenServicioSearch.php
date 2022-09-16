<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdenServicio;

/**
 * OrdenServicioSearch represents the model behind the search form of `app\models\OrdenServicio`.
 */
class OrdenServicioSearch extends OrdenServicio {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_orden_servicio', 'id_empresa', 'id_tipo_orden_servicio', 'id_cliente', 'id_estatus', 'id_unidad_vehicular', 'id_pozo', 'usuario_jefe_cuadrilla', 'usuario_cliente_solicitante', 'hora_salida', 'distancia_kms', 'combustible_aproximado_lts', 'ruta_descripcion', 'fecha', 'hora_entrada', 'origen_x', 'origen_y', 'destino_x', 'destino_y', 'fecha_hora_llegada_real', 'fecha_hora_salida_real', 'fecha_hora_inicio_trabajo', 'fecha_hora_final_trabajo', 'fecha_captura', 'usuario_captura', 'origen_version', 'fecha_version', 'usuario_version'], 'safe'],
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
        $query = OrdenServicio::find()
            ->joinWith([
                "cliente",
                "estatus",
                "pozo",
                "tipoOrdenServicio",
                "unidadVehicular",
                "usuarioCaptura",
                "usuarioClienteSolicitante",
                "usuarioJefeCuadrilla"
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
            'ordenes_servicio.id_orden_servicio' => $this->id_orden_servicio,
            'ordenes_servicio.id_empresa' => $this->id_empresa,
            'ordenes_servicio.id_tipo_orden_servicio' => $this->id_tipo_orden_servicio,
            'ordenes_servicio.id_cliente' => $this->id_cliente,
            'ordenes_servicio.id_estatus' => $this->id_estatus,
            'ordenes_servicio.id_unidad_vehicular' => $this->id_unidad_vehicular,
            'ordenes_servicio.id_pozo' => $this->id_pozo,
            'ordenes_servicio.usuario_jefe_cuadrilla' => $this->usuario_jefe_cuadrilla,
            'ordenes_servicio.usuario_cliente_solicitante' => $this->usuario_cliente_solicitante,
            'ordenes_servicio.hora_salida' => $this->hora_salida,
            'ordenes_servicio.distancia_kms' => $this->distancia_kms,
            'ordenes_servicio.combustible_aproximado_lts' => $this->combustible_aproximado_lts,
            'ordenes_servicio.fecha' => $this->fecha,
            'ordenes_servicio.hora_entrada' => $this->hora_entrada,
            'ordenes_servicio.fecha_hora_llegada_real' => $this->fecha_hora_llegada_real,
            'ordenes_servicio.fecha_hora_salida_real' => $this->fecha_hora_salida_real,
            'ordenes_servicio.fecha_hora_inicio_trabajo' => $this->fecha_hora_inicio_trabajo,
            'ordenes_servicio.fecha_hora_final_trabajo' => $this->fecha_hora_final_trabajo,
            'ordenes_servicio.fecha_captura' => $this->fecha_captura,
            'ordenes_servicio.usuario_captura' => $this->usuario_captura,
            'ordenes_servicio.origen_version' => $this->origen_version,
            'ordenes_servicio.fecha_version' => $this->fecha_version,
            'ordenes_servicio.usuario_version' => $this->usuario_version,
        ]);

        $query->andFilterWhere(['like', 'ordenes_servicio.ruta_descripcion', $this->ruta_descripcion])
            ->andFilterWhere(['like', 'ordenes_servicio.origen_x', $this->origen_x])
            ->andFilterWhere(['like', 'ordenes_servicio.origen_y', $this->origen_y])
            ->andFilterWhere(['like', 'ordenes_servicio.destino_x', $this->destino_x])
            ->andFilterWhere(['like', 'ordenes_servicio.destino_y', $this->destino_y]);

        return $dataProvider;
    }
}
