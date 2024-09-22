<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TiempoServicio;

/**
 * TiempoServicioSearch represents the model behind the search form of `app\models\TiempoServicio`.
 */
class TiempoServicioSearch extends TiempoServicio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tiempo'], 'integer'],
            [['tiempo_servicio', 'user_name'], 'safe'],
            [['horas_dia', 'pago_incapacidad_general', 'pago_incapacidad_laboral'], 'number'],
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
        $query = TiempoServicio::find();

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
            'id_tiempo' => $this->id_tiempo,
            'horas_dia' => $this->horas_dia,
            'pago_incapacidad_general' => $this->pago_incapacidad_general,
            'pago_incapacidad_laboral' => $this->pago_incapacidad_laboral,
        ]);

        $query->andFilterWhere(['like', 'tiempo_servicio', $this->tiempo_servicio])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
