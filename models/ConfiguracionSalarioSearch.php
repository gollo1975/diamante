<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConfiguracionSalario;

/**
 * ConfiguracionSalarioSearch represents the model behind the search form of `app\models\ConfiguracionSalario`.
 */
class ConfiguracionSalarioSearch extends ConfiguracionSalario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_salario', 'salario_minimo_actual', 'salario_minimo_anterior', 'auxilio_transporte_actual', 'auxilio_transporte_anterior', 'salario_incapacidad', 'anio', 'estado'], 'integer'],
            [['porcentaje_incremento'], 'number'],
            [['user_name', 'fecha_aplicacion', 'fecha_cierre', 'fecha_creacion'], 'safe'],
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
        $query = ConfiguracionSalario::find();

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
            'id_salario' => $this->id_salario,
            'salario_minimo_actual' => $this->salario_minimo_actual,
            'salario_minimo_anterior' => $this->salario_minimo_anterior,
            'auxilio_transporte_actual' => $this->auxilio_transporte_actual,
            'auxilio_transporte_anterior' => $this->auxilio_transporte_anterior,
            'salario_incapacidad' => $this->salario_incapacidad,
            'porcentaje_incremento' => $this->porcentaje_incremento,
            'anio' => $this->anio,
            'estado' => $this->estado,
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'fecha_cierre' => $this->fecha_cierre,
            'fecha_creacion' => $this->fecha_creacion,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
