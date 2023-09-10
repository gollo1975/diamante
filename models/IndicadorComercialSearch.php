<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\IndicadorComercial;

/**
 * IndicadorComercialSearch represents the model behind the search form of `app\models\IndicadorComercial`.
 */
class IndicadorComercialSearch extends IndicadorComercial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_indicador', 'anocierre', 'total_citas', 'total_citas_reales', 'total_citas_no_reales', 'total_porcentaje'], 'integer'],
            [['fecha_inicio', 'fecha_cierre', 'fecha_registro', 'user_name'], 'safe'],
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
        $query = IndicadorComercial::find();

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
            'id_indicador' => $this->id_indicador,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_cierre' => $this->fecha_cierre,
            'anocierre' => $this->anocierre,
            'fecha_registro' => $this->fecha_registro,
            'total_citas' => $this->total_citas,
            'total_citas_reales' => $this->total_citas_reales,
            'total_citas_no_reales' => $this->total_citas_no_reales,
            'total_porcentaje' => $this->total_porcentaje,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
