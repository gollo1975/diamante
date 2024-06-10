<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CierreCaja;

/**
 * CierreCajaSearch represents the model behind the search form of `app\models\CierreCaja`.
 */
class CierreCajaSearch extends CierreCaja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cierre', 'id_punto', 'total_remision', 'total_factura', 'total_efectivo_factura', 'total_efectivo_remision', 'total_transacion_factura', 'total_transacion_remision'], 'integer'],
            [['fecha_inicio', 'fecha_corte', 'user_name', 'fecha_hora_registro'], 'safe'],
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
        $query = CierreCaja::find();

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
            'id_cierre' => $this->id_cierre,
            'id_punto' => $this->id_punto,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_corte' => $this->fecha_corte,
            'total_remision' => $this->total_remision,
            'total_factura' => $this->total_factura,
            'total_efectivo_factura' => $this->total_efectivo_factura,
            'total_efectivo_remision' => $this->total_efectivo_remision,
            'total_transacion_factura' => $this->total_transacion_factura,
            'total_transacion_remision' => $this->total_transacion_remision,
            'fecha_hora_registro' => $this->fecha_hora_registro,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
