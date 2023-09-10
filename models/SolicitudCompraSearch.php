<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SolicitudCompra;

/**
 * SolicitudCompraSearch represents the model behind the search form of `app\models\SolicitudCompra`.
 */
class SolicitudCompraSearch extends SolicitudCompra
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_solicitud_compra', 'id_solicitud', 'id_area', 'user_name'], 'integer'],
            [['documento_soporte', 'fecha_creacion', 'fecha_entrega', 'observacion'], 'safe'],
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
        $query = SolicitudCompra::find();

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
            'id_solicitud_compra' => $this->id_solicitud_compra,
            'id_solicitud' => $this->id_solicitud,
            'id_area' => $this->id_area,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_entrega' => $this->fecha_entrega,
            'user_name' => $this->user_name,
        ]);

        $query->andFilterWhere(['like', 'documento_soporte', $this->documento_soporte])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
