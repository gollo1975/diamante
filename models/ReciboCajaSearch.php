<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReciboCaja;

/**
 * ReciboCajaSearch represents the model behind the search form of `app\models\ReciboCaja`.
 */
class ReciboCajaSearch extends ReciboCaja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_recibo', 'numero_recibo', 'id_cliente', 'id_tipo', 'valor_pago', 'autorizado'], 'integer'],
            [['fecha_pago', 'fecha_proceso', 'codigo_municipio', 'codigo_banco', 'observacion', 'user_name'], 'safe'],
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
        $query = ReciboCaja::find();

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
            'numero_recibo' => $this->numero_recibo,
            'id_cliente' => $this->id_cliente,
            'id_tipo' => $this->id_tipo,
            'fecha_pago' => $this->fecha_pago,
            'fecha_proceso' => $this->fecha_proceso,
            'valor_pago' => $this->valor_pago,
            'autorizado' => $this->autorizado,
        ]);

        $query->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio])
            ->andFilterWhere(['like', 'codigo_banco', $this->codigo_banco])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
