<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DevolucionProductos;

/**
 * DevolucionProductosSearch represents the model behind the search form of `app\models\DevolucionProductos`.
 */
class DevolucionProductosSearch extends DevolucionProductos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_devolucion', 'id_cliente', 'id_nota', 'cantidad'], 'integer'],
            [['fecha_devolucion'], 'number'],
            [['fecha_registro', 'user_name', 'observacion'], 'safe'],
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
        $query = DevolucionProductos::find();

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
            'id_devolucion' => $this->id_devolucion,
            'id_cliente' => $this->id_cliente,
            'id_nota' => $this->id_nota,
            'fecha_devolucion' => $this->fecha_devolucion,
            'cantidad' => $this->cantidad,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
