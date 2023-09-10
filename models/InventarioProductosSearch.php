<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InventarioProductos;

/**
 * InventarioProductosSearch represents the model behind the search form of `app\models\InventarioProductos`.
 */
class InventarioProductosSearch extends InventarioProductos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'codigo_producto', 'costo_unitario', 'unidades_entradas', 'stock_unidades', 'id_medida_producto', 'id_detalle', 'aplica_iva', 'inventario_inicial', 'aplica_inventario', 'subtotal', 'valor_iva', 'total_inventario', 'precio_venta_uno', 'precio_venta_dos', 'precio_venta_tres', 'codigo_ean'], 'integer'],
            [['nombre_producto', 'descripcion_producto', 'fecha_vencimiento', 'fecha_creacion', 'fecha_proceso', 'user_name'], 'safe'],
            [['porcentaje_iva'], 'number'],
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
        $query = InventarioProductos::find();

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
            'id_inventario' => $this->id_inventario,
            'codigo_producto' => $this->codigo_producto,
            'costo_unitario' => $this->costo_unitario,
            'unidades_entradas' => $this->unidades_entradas,
            'stock_unidades' => $this->stock_unidades,
            'id_medida_producto' => $this->id_medida_producto,
            'id_detalle' => $this->id_detalle,
            'aplica_iva' => $this->aplica_iva,
            'inventario_inicial' => $this->inventario_inicial,
            'aplica_inventario' => $this->aplica_inventario,
            'porcentaje_iva' => $this->porcentaje_iva,
            'subtotal' => $this->subtotal,
            'valor_iva' => $this->valor_iva,
            'total_inventario' => $this->total_inventario,
            'precio_venta_uno' => $this->precio_venta_uno,
            'precio_venta_dos' => $this->precio_venta_dos,
            'precio_venta_tres' => $this->precio_venta_tres,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_proceso' => $this->fecha_proceso,
            'codigo_ean' => $this->codigo_ean,
        ]);

        $query->andFilterWhere(['like', 'nombre_producto', $this->nombre_producto])
            ->andFilterWhere(['like', 'descripcion_producto', $this->descripcion_producto])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
