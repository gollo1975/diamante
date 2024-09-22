<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CajaCompensacion;

/**
 * CajaCompensacionSearch represents the model behind the search form of `app\models\CajaCompensacion`.
 */
class CajaCompensacionSearch extends CajaCompensacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_caja', 'estado'], 'integer'],
            [['caja', 'telefono', 'direccion', 'codigo', 'codigo_municipio', 'user_name', 'fecha_hora_registro'], 'safe'],
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
        $query = CajaCompensacion::find();

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
            'id_caja' => $this->id_caja,
            'estado' => $this->estado,
            'porcentaje' => $this->porcentaje,
            'fecha_hora_registro' => $this->fecha_hora_registro,
        ]);

        $query->andFilterWhere(['like', 'caja', $this->caja])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'codigo', $this->codigo])
            ->andFilterWhere(['like', 'codigo_municipio', $this->codigo_municipio])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
