<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ResolucionDian;

/**
 * ResolucionDianSearch represents the model behind the search form of `app\models\ResolucionDian`.
 */
class ResolucionDianSearch extends ResolucionDian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_resolucion', 'estado_resolucion','id_documento'], 'integer'],
            [['numero_resolucion', 'desde', 'hasta', 'fecha_vence', 'consecutivo', 'fecha_registro', 'user_name'], 'safe'],
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
        $query = ResolucionDian::find();

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
            'id_resolucion' => $this->id_resolucion,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
            'fecha_vence' => $this->fecha_vence,
            'fecha_registro' => $this->fecha_registro,
            'estado_resolucion' => $this->estado_resolucion,
            'id_documento' => $this->id_documento,
        ]);

        $query->andFilterWhere(['like', 'numero_resolucion', $this->numero_resolucion])
            ->andFilterWhere(['like', 'consecutivo', $this->consecutivo])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
             ->andFilterWhere(['=', 'id_documento', $this->id_documento]);

        return $dataProvider;
    }
}
