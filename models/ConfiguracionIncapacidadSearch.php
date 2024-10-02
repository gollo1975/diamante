<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConfiguracionIncapacidad;

/**
 * ConfiguracionIncapacidadSearch represents the model behind the search form of `app\models\ConfiguracionIncapacidad`.
 */
class ConfiguracionIncapacidadSearch extends ConfiguracionIncapacidad
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_incapacidad', 'genera_pago', 'genera_ibc', 'codigo_salario', 'codigo'], 'integer'],
            [['nombre', 'user_name'], 'safe'],
            [['porcentaje'], 'number'],
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
        $query = ConfiguracionIncapacidad::find();

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
            'codigo_incapacidad' => $this->codigo_incapacidad,
            'genera_pago' => $this->genera_pago,
            'genera_ibc' => $this->genera_ibc,
            'codigo_salario' => $this->codigo_salario,
            'porcentaje' => $this->porcentaje,
            'codigo' => $this->codigo,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
