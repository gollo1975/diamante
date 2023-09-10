<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pedidos;

/**
 * PedidosSearch represents the model behind the search form of `app\models\Pedidos`.
 */
class PedidosSearch extends Pedidos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pedido', 'numero_pedido', 'id_cliente', 'dv', 'cantidad', 'subtotal', 'impuesto', 'gran_total', 'autorizado', 'cerrar_pedido', 'facturado'], 'integer'],
            [['documento', 'cliente', 'usuario', 'fecha_proceso'], 'safe'],
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
        $query = Pedidos::find();

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
            'id_pedido' => $this->id_pedido,
            'numero_pedido' => $this->numero_pedido,
            'id_cliente' => $this->id_cliente,
            'dv' => $this->dv,
            'cantidad' => $this->cantidad,
            'subtotal' => $this->subtotal,
            'impuesto' => $this->impuesto,
            'gran_total' => $this->gran_total,
            'autorizado' => $this->autorizado,
            'cerrar_pedido' => $this->cerrar_pedido,
            'fecha_proceso' => $this->fecha_proceso,
            'facturado' => $this->facturado,
        ]);

        $query->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'cliente', $this->cliente])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
