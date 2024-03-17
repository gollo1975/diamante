<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProveedorEstudios;

/**
 * ProveedorEstudiosSearch represents the model behind the search form of `app\models\ProveedorEstudios`.
 */
class ProveedorEstudiosSearch extends ProveedorEstudios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_estudio', 'id_tipo_documento', 'dv'], 'integer'],
            [['nit_cedula', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'razon_social', 'nombre_completo'], 'safe'],
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
        $query = ProveedorEstudios::find();

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
            'id_estudio' => $this->id_estudio,
            'id_tipo_documento' => $this->id_tipo_documento,
            'dv' => $this->dv,
        ]);

        $query->andFilterWhere(['like', 'nit_cedula', $this->nit_cedula])
            ->andFilterWhere(['like', 'primer_nombre', $this->primer_nombre])
            ->andFilterWhere(['like', 'segundo_nombre', $this->segundo_nombre])
            ->andFilterWhere(['like', 'primer_apellido', $this->primer_apellido])
            ->andFilterWhere(['like', 'segundo_apellido', $this->segundo_apellido])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'nombre_completo', $this->nombre_completo]);

        return $dataProvider;
    }
}
