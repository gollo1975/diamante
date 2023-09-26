<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoFacturaVenta;

/**
 * TipoFacturaVentaSearch represents the model behind the search form of `app\models\TipoFacturaVenta`.
 */
class TipoFacturaVentaSearch extends TipoFacturaVenta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_factura','base_retencion'], 'integer'],
            [['porcentaje_retencion'], 'number'],
            [['descripcion', 'user_name', 'fecha_registro'], 'safe'],
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
        $query = TipoFacturaVenta::find();

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
            'id_tipo_factura' => $this->id_tipo_factura,
            'fecha_registro' => $this->fecha_registro,
            'porcentaje_retencion' => $this->porcentaje_retencion,
            'base_retencion' => $this->base_retencion,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
