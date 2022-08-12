<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ParametrosValores;

/**
 * ParametrosValoresSearch represents the model behind the search form of `app\models\ParametrosValores`.
 */
class ParametrosValoresSearch extends ParametrosValores
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pav_clave', 'pav_activo', 'pav_fkpar', 'pav_fkemp', 'pav_usuarioVersion'], 'integer'],
            [['pav_valor', 'pav_fechaVersion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = ParametrosValores::find();

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
            'pav_clave' => $this->pav_clave,
            'pav_activo' => $this->pav_activo,
            'pav_fkpar' => $this->pav_fkpar,
            'pav_fkemp' => $this->pav_fkemp,
            'pav_fechaVersion' => $this->pav_fechaVersion,
            'pav_usuarioVersion' => $this->pav_usuarioVersion,
        ]);

        $query->andFilterWhere(['like', 'pav_valor', $this->pav_valor]);

        return $dataProvider;
    }
}
