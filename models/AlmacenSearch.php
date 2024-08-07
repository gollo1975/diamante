<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Almacen;

/**
 * AlmacenSearch represents the model behind the search form of `app\models\Almacen`.
 */
class AlmacenSearch extends Almacen
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_almacen','predeterminado'], 'integer'],
            [['almacen', 'fecha_registro', 'user_name'], 'safe'],
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
        $query = Almacen::find();

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
            'id_almacen' => $this->id_almacen,
            'fecha_registro' => $this->fecha_registro,
            'predeterminado' => $this->predeterminado,
        ]);

        $query->andFilterWhere(['like', 'almacen', $this->almacen])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
