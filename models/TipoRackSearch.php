<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoRack;

/**
 * TipoRackSearch represents the model behind the search form of `app\models\TipoRack`.
 */
class TipoRackSearch extends TipoRack
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rack', 'numero_rack'], 'integer'],
            [['descripcion', 'user_name'], 'safe'],
            [['medida_ancho', 'media_alto', 'total_peso'], 'number'],
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
        $query = TipoRack::find();

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
            'id_rack' => $this->id_rack,
            'numero_rack' => $this->numero_rack,
            'medida_ancho' => $this->medida_ancho,
            'media_alto' => $this->media_alto,
            'total_peso' => $this->total_peso,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
