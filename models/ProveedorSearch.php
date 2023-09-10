<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proveedor;

/**
 * ProveedorSearch represents the model behind the search form of `app\models\Proveedor`.
 */
class ProveedorSearch extends Proveedor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_provedor', 'id_tipo_documento', 'tipo_regimen', 'forma_pago', 'plazo', 'autoretenedor', 'id_naturaleza', 'tipo_sociedad', 'tipo_cuenta', 'tipo_transacion', 'id_empresa'], 'integer'],
            [['nit/cedula', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'razon_social', 'nombre_completo', 'direccion', 'email', 'telefono', 'celular', 'codigo_departamento', 'codigo_municipio', 'nombre_contacto', 'celular_contacto', 'codigo_banco', 'producto', 'user_name', 'fecha_creacion'], 'safe'],
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
        $query = Proveedor::find();

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
            'id_provedor' => $this->id_provedor,
            'id_tipo_documento' => $this->id_tipo_documento,
            'tipo_regimen' => $this->tipo_regimen,
            'forma_pago' => $this->forma_pago,
            'plazo' => $this->plazo,
            'autoretenedor' => $this->autoretenedor,
            'id_naturaleza' => $this->id_naturaleza,
            'tipo_sociedad' => $this->tipo_sociedad,
            'tipo_cuenta' => $this->tipo_cuenta,
            'tipo_transacion' => $this->tipo_transacion,
            'fecha_creacion' => $this->fecha_creacion,
            'id_empresa' => $this->id_empresa,
        ]);

        $query->andFilterWhere(['like', 'nit/cedula', $this->nit/cedula])
            ->andFilterWhere(['like', 'primer_nombre', $this->primer_nombre])
            ->andFilterWhere(['like', 'segundo_nombre', $this->segundo_nombre])
            ->andFilterWhere(['like', 'primer_apellido', $this->primer_apellido])
            ->andFilterWhere(['like', 'segundo_apellido', $this->segundo_apellido])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'nombre_completo', $this->nombre_completo])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'codigo_departamento', $this->codigo_departamento])
            ->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio])
            ->andFilterWhere(['like', 'nombre_contacto', $this->nombre_contacto])
            ->andFilterWhere(['like', 'celular_contacto', $this->celular_contacto])
            ->andFilterWhere(['like', 'codigo_banco', $this->codigo_banco])
            ->andFilterWhere(['like', 'producto', $this->producto])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
