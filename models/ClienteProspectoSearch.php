<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClienteProspecto;

/**
 * ClienteProspectoSearch represents the model behind the search form of `app\models\ClienteProspecto`.
 */
class ClienteProspectoSearch extends ClienteProspecto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_prospecto', 'id_tipo_documento', 'dv', 'id_agente'], 'integer'],
            [['nit_cedula', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'razon_social', 'celular', 'email_prospecto', 'codigo_departamento', 'codigo_municipio', 'user_name', 'fecha_registro'], 'safe'],
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
        $query = ClienteProspecto::find();

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
            'id_prospecto' => $this->id_prospecto,
            'id_tipo_documento' => $this->id_tipo_documento,
            'dv' => $this->dv,
            'id_agente' => $this->id_agente,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'nit_cedula', $this->nit_cedula])
            ->andFilterWhere(['like', 'primer_nombre', $this->primer_nombre])
            ->andFilterWhere(['like', 'segundo_nombre', $this->segundo_nombre])
            ->andFilterWhere(['like', 'primer_apellido', $this->primer_apellido])
            ->andFilterWhere(['like', 'segundo_apellido', $this->segundo_apellido])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'email_prospecto', $this->email_prospecto])
            ->andFilterWhere(['like', 'codigo_departamento', $this->codigo_departamento])
            ->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
