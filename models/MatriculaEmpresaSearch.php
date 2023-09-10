<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MatriculaEmpresa;

/**
 * MatriculaEmpresaSearch represents the model behind the search form of `app\models\MatriculaEmpresa`.
 */
class MatriculaEmpresaSearch extends MatriculaEmpresa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'nit_empresa', 'dv', 'id_resolucion'], 'integer'],
            [['razon_social', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'razon_social_completa', 'direccion', 'telefono', 'celular', 'codigo_departamento', 'codigo_municipio'], 'string'],
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
        $query = MatriculaEmpresa::find();

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
            'id_empresa' => $this->id_empresa,
            'nit_empresa' => $this->nit_empresa,
            'dv' => $this->dv,
            'id_resolucion' => $this->id_resolucion,
        ]);

        $query->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'primer_nombre', $this->primer_nombre])
            ->andFilterWhere(['like', 'segundo_nombre', $this->segundo_nombre])
            ->andFilterWhere(['like', 'primer_apellido', $this->primer_apellido])
            ->andFilterWhere(['like', 'segundo_apellido', $this->segundo_apellido])
            ->andFilterWhere(['like', 'razon_social_completa', $this->razon_social_completa])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'codigo_departamento', $this->codigo_departamento])
            ->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio]);

        return $dataProvider;
    }
}
