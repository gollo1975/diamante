<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PagoBanco;

/**
 * PagoBancoSearch represents the model behind the search form of `app\models\PagoBanco`.
 */
class PagoBancoSearch extends PagoBanco
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pago_banco', 'id_empresa', 'nit_cedula', 'tipo_pago', 'id_tipo_nomina', 'total_empleados', 'total_pagar', 'debitos', 'autorizado', 'cerrar_proceso'], 'integer'],
            [['codigo_banco', 'aplicacion', 'secuencia', 'fecha_creacion', 'fecha_aplicacion', 'adicion_numero', 'descripcion', 'user_name', 'fecha_hora_registro'], 'safe'],
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
        $query = PagoBanco::find();

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
            'id_pago_banco' => $this->id_pago_banco,
            'id_empresa' => $this->id_empresa,
            'nit_cedula' => $this->nit_cedula,
            'tipo_pago' => $this->tipo_pago,
            'id_tipo_nomina' => $this->id_tipo_nomina,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'total_empleados' => $this->total_empleados,
            'total_pagar' => $this->total_pagar,
            'debitos' => $this->debitos,
            'autorizado' => $this->autorizado,
            'cerrar_proceso' => $this->cerrar_proceso,
            'fecha_hora_registro' => $this->fecha_hora_registro,
        ]);

        $query->andFilterWhere(['like', 'codigo_banco', $this->codigo_banco])
            ->andFilterWhere(['like', 'aplicacion', $this->aplicacion])
            ->andFilterWhere(['like', 'secuencia', $this->secuencia])
            ->andFilterWhere(['like', 'adicion_numero', $this->adicion_numero])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
