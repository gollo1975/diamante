<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MedidaProductoTerminado;

/**
 * MedidaProductoTerminadoSearch represents the model behind the search form of `app\models\MedidaProductoTerminado`.
 */
class MedidaProductoTerminadoSearch extends MedidaProductoTerminado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_medida_producto'], 'integer'],
            [['descripcion','codigo_enlace'], 'safe'],
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
        $query = MedidaProductoTerminado::find();

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
            'id_medida_producto' => $this->id_medida_producto,
            'codigo_enlace' => $this->codigo_enlace,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);
        $query->andFilterWhere(['=', 'codigo_enlace', $this->codigo_enlace]);

        return $dataProvider;
    }
}
