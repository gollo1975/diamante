<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntidadBancarias;

/**
 * EntidadBancariasSearch represents the model behind the search form of `app\models\EntidadBancarias`.
 */
class EntidadBancariasSearch extends EntidadBancarias
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_banco', 'entidad_bancaria', 'direccion_banco', 'telefono_banco', 'producto', 'user_name', 'codigo_interfaz'], 'safe'],
            [['nit_banco', 'dv', 'tipo_producto', 'id_empresa', 'convenio_nomina', 'convenio_proveedor', 'convenio_empresa', 'estado_registro'], 'integer'],
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
        $query = EntidadBancarias::find();

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
            'nit_banco' => $this->nit_banco,
            'dv' => $this->dv,
            'tipo_producto' => $this->tipo_producto,
            'id_empresa' => $this->id_empresa,
            'convenio_nomina' => $this->convenio_nomina,
            'convenio_proveedor' => $this->convenio_proveedor,
            'convenio_empresa' => $this->convenio_empresa,
            'estado_registro' => $this->estado_registro,
        ]);

        $query->andFilterWhere(['like', 'codigo_banco', $this->codigo_banco])
            ->andFilterWhere(['like', 'entidad_bancaria', $this->entidad_bancaria])
            ->andFilterWhere(['like', 'direccion_banco', $this->direccion_banco])
            ->andFilterWhere(['like', 'telefono_banco', $this->telefono_banco])
            ->andFilterWhere(['like', 'producto', $this->producto])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'codigo_interfaz', $this->codigo_interfaz]);

        return $dataProvider;
    }
}
