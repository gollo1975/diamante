<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GrupoPago;

/**
 * GrupoPagoSearch represents the model behind the search form of `app\models\GrupoPago`.
 */
class GrupoPagoSearch extends GrupoPago
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo_pago', 'id_sucursal', 'limite_devengado', 'dias_pago', 'estado','id_periodo_pago'], 'integer'],
            [['grupo_pago', 'codigo_departamento', 'codigo_municipio', 'ultimo_pago_nomina', 'ultimo_pago_prima', 'ultimo_pago_cesantia', 'observacion', 'user_name', 'fecha_hora_registro'], 'safe'],
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
        $query = GrupoPago::find();

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
            'id_grupo_pago' => $this->id_grupo_pago,
            'id_sucursal' => $this->id_sucursal,
            'ultimo_pago_nomina' => $this->ultimo_pago_nomina,
            'ultimo_pago_prima' => $this->ultimo_pago_prima,
            'ultimo_pago_cesantia' => $this->ultimo_pago_cesantia,
            'limite_devengado' => $this->limite_devengado,
            'dias_pago' => $this->dias_pago,
            'estado' => $this->estado,
            'id_periodo_pago' => $this->id_periodo_pago,
            'fecha_hora_registro' => $this->fecha_hora_registro,
        ]);

        $query->andFilterWhere(['like', 'grupo_pago', $this->grupo_pago])
            ->andFilterWhere(['like', 'codigo_departamento', $this->codigo_departamento])
            ->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
             ->andFilterWhere(['=', 'id_periodo_pago', $this->id_periodo_pago]);

        return $dataProvider;
    }
}
