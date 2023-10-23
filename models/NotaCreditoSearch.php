<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NotaCredito;

/**
 * NotaCreditoSearch represents the model behind the search form of `app\models\NotaCredito`.
 */
class NotaCreditoSearch extends NotaCredito
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_nota', 'numero_nota_credito', 'id_cliente', 'id_motivo', 'id_factura', 'id_tipo_factura', 'valor_devolucion', 'valor_bruto', 'impuesto', 'retencion', 'rete_iva', 'valor_total_devolucion', 'autorizado', 'cerrar_nota', 'nuevo_saldo'], 'integer'],
            [['nit_cedula', 'cliente', 'cufe_factura', 'fecha_factura', 'fecha_nota_credito', 'fecha_enviada', 'user_name'], 'safe'],
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
        $query = NotaCredito::find();

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
            'id_nota' => $this->id_nota,
            'numero_nota_credito' => $this->numero_nota_credito,
            'id_cliente' => $this->id_cliente,
            'id_motivo' => $this->id_motivo,
            'id_factura' => $this->id_factura,
            'id_tipo_factura' => $this->id_tipo_factura,
            'fecha_factura' => $this->fecha_factura,
            'fecha_nota_credito' => $this->fecha_nota_credito,
            'fecha_enviada' => $this->fecha_enviada,
            'valor_devolucion' => $this->valor_devolucion,
            'valor_bruto' => $this->valor_bruto,
            'impuesto' => $this->impuesto,
            'retencion' => $this->retencion,
            'rete_iva' => $this->rete_iva,
            'valor_total_devolucion' => $this->valor_total_devolucion,
            'autorizado' => $this->autorizado,
            'cerrar_nota' => $this->cerrar_nota,
            'nuevo_saldo' => $this->nuevo_saldo,
        ]);

        $query->andFilterWhere(['like', 'nit_cedula', $this->nit_cedula])
            ->andFilterWhere(['like', 'cliente', $this->cliente])
            ->andFilterWhere(['like', 'cufe_factura', $this->cufe_factura])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
