<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Clientes;

/**
 * ClientesSearch represents the model behind the search form of `app\models\Clientes`.
 */
class ClientesSearch extends Clientes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_tipo_documento', 'dv', 'tipo_regimen', 'forma_pago', 'plazo', 'autoretenedor', 'id_naturaleza', 'tipo_sociedad', 'id_posicion'], 'integer'],
            [['nit_cedula', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'direccion', 'telefono', 'celular', 'email_cliente', 'codigo_departamento', 'codigo_municipio', 'user_name', 'fecha_creacion', 'user_name_editar', 'fecha_editado', 'observacion'], 'safe'],
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
        $query = Clientes::find();

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
            'id_cliente' => $this->id_cliente,
            'id_tipo_documento' => $this->id_tipo_documento,
            'dv' => $this->dv,
            'tipo_regimen' => $this->tipo_regimen,
            'forma_pago' => $this->forma_pago,
            'plazo' => $this->plazo,
            'autoretenedor' => $this->autoretenedor,
            'id_naturaleza' => $this->id_naturaleza,
            'tipo_sociedad' => $this->tipo_sociedad,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_editado' => $this->fecha_editado,
            'id_posicion' => $this->id_posicion,
        ]);

        $query->andFilterWhere(['like', 'nit_cedula', $this->nit_cedula])
            ->andFilterWhere(['like', 'primer_nombre', $this->primer_nombre])
            ->andFilterWhere(['like', 'segundo_nombre', $this->segundo_nombre])
            ->andFilterWhere(['like', 'primer_apellido', $this->primer_apellido])
            ->andFilterWhere(['like', 'segundo_apellido', $this->segundo_apellido])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'email_cliente', $this->email_cliente])
            ->andFilterWhere(['like', 'codigo_departamento', $this->codigo_departamento])
            ->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_name_editar', $this->user_name_editar])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
