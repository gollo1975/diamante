<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProgramacionCitas;

/**
 * ProgramacionCitasSearch represents the model behind the search form of `app\models\ProgramacionCitas`.
 */
class ProgramacionCitasSearch extends ProgramacionCitas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programacion', 'id_agente', 'total_citas'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'fecha_registro', 'user_name'], 'safe'],
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
        $query = ProgramacionCitas::find();

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
            'id_programacion' => $this->id_programacion,
            'id_agente' => $this->id_agente,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_final' => $this->fecha_final,
            'fecha_registro' => $this->fecha_registro,
            'total_citas' => $this->total_citas,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
