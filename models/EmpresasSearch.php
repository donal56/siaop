<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empresas;

/**
 * EmpresaSerach represents the model behind the search form of `app\models\Empresa`.
 */
class EmpresasSearch extends Empresas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_activo'], 'integer'],
            [['emp_nombre', 'emp_descripcion', 'emp_codigo'], 'safe'],
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
        $query = Empresas::find();

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
            'emp_clave' => $this->emp_clave,
            'emp_codigo' => $this->emp_codigo,
            'emp_activo' => $this->emp_activo,
            'emp_fechaVersion' => $this->emp_fechaVersion,
            'emp_usuarioVersion' => $this->emp_usuarioVersion,
        ]);

        $query->andFilterWhere(['like', 'emp_nombre', $this->emp_nombre])
            ->andFilterWhere(['like', 'emp_descripcion', $this->emp_descripcion]);

        return $dataProvider;
    }
}
