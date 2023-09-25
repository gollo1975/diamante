<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FacturaVenta;

/**
 * FacturaVentaSearch represents the model behind the search form of `app\models\FacturaVenta`.
 */
class FacturaVentaSearch extends FacturaVenta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_factura', 'id_pedido', 'id_cliente', 'id_tipo_factura', 'numero_factura', 'dv', 'subtotal_factura', 'descuento', 'impuesto', 'total_factura', 'valor_retencion', 'valor_reteiva', 'saldo_factura', 'forma_pago', 'plazo_pago', 'autorizado'], 'integer'],
            [['nit_cedula', 'cliente', 'numero_resolucion', 'desde', 'hasta', 'consecutivo', 'fecha_inicio', 'fecha_vencimiento', 'fecha_generada', 'fecha_enviada', 'user_name'], 'safe'],
            [['porcentaje_iva', 'porcentaje_rete_iva', 'porcentaje_rete_fuente', 'porcentaje_descuento'], 'number'],
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
        $query = FacturaVenta::find();

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
            'id_factura' => $this->id_factura,
            'id_pedido' => $this->id_pedido,
            'id_cliente' => $this->id_cliente,
            'id_tipo_factura' => $this->id_tipo_factura,
            'numero_factura' => $this->numero_factura,
            'dv' => $this->dv,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'fecha_generada' => $this->fecha_generada,
            'fecha_enviada' => $this->fecha_enviada,
            'subtotal_factura' => $this->subtotal_factura,
            'descuento' => $this->descuento,
            'impuesto' => $this->impuesto,
            'total_factura' => $this->total_factura,
            'porcentaje_iva' => $this->porcentaje_iva,
            'porcentaje_rete_iva' => $this->porcentaje_rete_iva,
            'porcentaje_rete_fuente' => $this->porcentaje_rete_fuente,
            'valor_retencion' => $this->valor_retencion,
            'valor_reteiva' => $this->valor_reteiva,
            'porcentaje_descuento' => $this->porcentaje_descuento,
            'saldo_factura' => $this->saldo_factura,
            'forma_pago' => $this->forma_pago,
            'plazo_pago' => $this->plazo_pago,
            'autorizado' => $this->autorizado,
        ]);

        $query->andFilterWhere(['like', 'nit_cedula', $this->nit_cedula])
            ->andFilterWhere(['like', 'cliente', $this->cliente])
            ->andFilterWhere(['like', 'numero_resolucion', $this->numero_resolucion])
            ->andFilterWhere(['like', 'consecutivo', $this->consecutivo])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
