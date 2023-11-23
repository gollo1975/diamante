<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AlmacenamientoProducto;

/**
 * AlmacenamientoProductoSearch represents the model behind the search form of `app\models\AlmacenamientoProducto`.
 */
class AlmacenamientoProductoSearch extends AlmacenamientoProducto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_almacenamiento', 'id_orden_produccion', 'id_documento', 'id_rack', 'unidades_producidas', 'unidades_almacenadas', 'unidades_faltantes'], 'integer'],
            [['codigo_producto', 'nombre_producto', 'fecha_almacenamiento', 'user_name'], 'safe'],
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
        $query = AlmacenamientoProducto::find();

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
            'id_almacenamiento' => $this->id_almacenamiento,
            'id_orden_produccion' => $this->id_orden_produccion,
            'id_documento' => $this->id_documento,
            'id_rack' => $this->id_rack,
            'unidades_producidas' => $this->unidades_producidas,
            'unidades_almacenadas' => $this->unidades_almacenadas,
            'unidades_faltantes' => $this->unidades_faltantes,
            'fecha_almacenamiento' => $this->fecha_almacenamiento,
        ]);

        $query->andFilterWhere(['like', 'codigo_producto', $this->codigo_producto])
            ->andFilterWhere(['like', 'nombre_producto', $this->nombre_producto])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
