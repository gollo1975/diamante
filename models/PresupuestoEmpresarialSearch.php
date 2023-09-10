<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PresupuestoEmpresarial;

/**
 * PresupuestoEmpresarialSearch represents the model behind the search form of `app\models\PresupuestoEmpresarial`.
 */
class PresupuestoEmpresarialSearch extends PresupuestoEmpresarial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_presupuesto', 'valor_presupuesto', 'id_area', 'año', 'estado'], 'integer'],
            [['descripcion', 'fecha_inicio', 'fecha_corte', 'user_name', 'fecha_registro'], 'safe'],
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
        $query = PresupuestoEmpresarial::find();

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
            'id_presupuesto' => $this->id_presupuesto,
            'valor_presupuesto' => $this->valor_presupuesto,
            'id_area' => $this->id_area,
            'año' => $this->año,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_corte' => $this->fecha_corte,
            'estado' => $this->estado,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
