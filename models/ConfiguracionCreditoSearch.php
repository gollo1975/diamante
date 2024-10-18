<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConfiguracionCredito;

/**
 * ConfiguracionCreditoSearch represents the model behind the search form of `app\models\ConfiguracionCredito`.
 */
class ConfiguracionCreditoSearch extends ConfiguracionCredito
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_credito', 'codigo_salario'], 'integer'],
            [['nombre_credito'], 'safe'],
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
        $query = ConfiguracionCredito::find();

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
            'codigo_credito' => $this->codigo_credito,
            'codigo_salario' => $this->codigo_salario,
        ]);

        $query->andFilterWhere(['like', 'nombre_credito', $this->nombre_credito]);

        return $dataProvider;
    }
}
