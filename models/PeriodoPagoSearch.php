<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PeriodoPago;

/**
 * PeriodoPagoSearch represents the model behind the search form of `app\models\PeriodoPago`.
 */
class PeriodoPagoSearch extends PeriodoPago
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_periodo_pago', 'dias', 'limite_horas', 'continua'], 'integer'],
            [['nombre_periodo'], 'safe'],
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
        $query = PeriodoPago::find();

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
            'id_periodo_pago' => $this->id_periodo_pago,
            'dias' => $this->dias,
            'limite_horas' => $this->limite_horas,
            'continua' => $this->continua,
        ]);

        $query->andFilterWhere(['like', 'nombre_periodo', $this->nombre_periodo]);

        return $dataProvider;
    }
}
