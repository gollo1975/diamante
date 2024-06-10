<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReciboCajaPuntoVenta;

/**
 * ReciboCajaPuntoVentaSearch represents the model behind the search form of `app\models\ReciboCajaPuntoVenta`.
 */
class ReciboCajaPuntoVentaSearch extends ReciboCajaPuntoVenta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_recibo', 'id_remision', 'id_factura', 'id_tipo', 'id_punto', 'numero_recibo', 'valor_abono', 'valor_saldo'], 'integer'],
            [['fecha_recibo', 'user_name'], 'safe'],
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
        $query = ReciboCajaPuntoVenta::find();

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
            'id_recibo' => $this->id_recibo,
            'id_remision' => $this->id_remision,
            'id_factura' => $this->id_factura,
            'id_tipo' => $this->id_tipo,
            'id_punto' => $this->id_punto,
            'fecha_recibo' => $this->fecha_recibo,
            'numero_recibo' => $this->numero_recibo,
            'valor_abono' => $this->valor_abono,
            'valor_saldo' => $this->valor_saldo,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
