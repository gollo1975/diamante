<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoOrdenCompra;

/**
 * TipoOrdenCompraSearch represents the model behind the search form of `app\models\TipoOrdenCompra`.
 */
class TipoOrdenCompraSearch extends TipoOrdenCompra
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_orden'], 'integer'],
            [['descripcion_orden'], 'safe'],
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
        $query = TipoOrdenCompra::find();

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
            'id_tipo_orden' => $this->id_tipo_orden,
        ]);

        $query->andFilterWhere(['like', 'descripcion_orden', $this->descripcion_orden]);

        return $dataProvider;
    }
}
