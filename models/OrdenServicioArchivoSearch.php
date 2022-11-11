<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdenServicioArchivo;

/**
 * OrdenServicioArchivoSearch represents the model behind the search form of `app\models\OrdenServicioArchivo`.
 */
class OrdenServicioArchivoSearch extends OrdenServicioArchivo {
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_orden_servicio_archivo', 'id_orden_servicio', 'id_archivo', 'id_tipo_archivo', 'validado', 'usuario_validacion'], 'integer'],
            [['fecha_validacion'], 'safe'],
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
        $query = OrdenServicioArchivo::find()
            ->joinWith([
                "archivo",
                "ordenServicio",
                "tipoArchivo",
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
            'ordenes_servicio_archivos.id_orden_servicio_archivo' => $this->id_orden_servicio_archivo,
            'ordenes_servicio_archivos.id_orden_servicio' => $this->id_orden_servicio,
            'ordenes_servicio_archivos.id_archivo' => $this->id_archivo,
            'ordenes_servicio_archivos.id_tipo_archivo' => $this->id_tipo_archivo,
            'ordenes_servicio_archivos.validado' => $this->validado,
            'ordenes_servicio_archivos.usuario_validacion' => $this->usuario_validacion,
            'ordenes_servicio_archivos.fecha_validacion' => $this->fecha_validacion,
        ]);

        return $dataProvider;
    }
}
