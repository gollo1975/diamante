<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "packing_pedido_detalle".
 *
 * @property int $id_detalle
 * @property int $codigo_producto
 * @property string $nombre_producto
 * @property string $fecha_packing
 * @property string $fecha_cracion_packing
 * @property int $cantidad_despachada
 * @property int $id_packing
 * @property int $id_inventario
 *
 * @property PackingPedido $packing
 * @property InventarioProductos $inventario
 */
class PackingPedidoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packing_pedido_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_producto', 'cantidad_despachada', 'id_packing', 'id_inventario','numero_caja','linea_duplicada'], 'integer'],
            [['fecha_packing', 'fecha_cracion_packing'], 'safe'],
            [['nombre_producto','numero_guia'], 'string', 'max' => 50],
            [['id_packing'], 'exist', 'skipOnError' => true, 'targetClass' => PackingPedido::className(), 'targetAttribute' => ['id_packing' => 'id_packing']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'codigo_producto' => 'Codigo Producto',
            'nombre_producto' => 'Nombre Producto',
            'fecha_packing' => 'Fecha Packing',
            'fecha_cracion_packing' => 'Fecha Cracion Packing',
            'cantidad_despachada' => 'Cantidad Despachada',
            'id_packing' => 'Id Packing',
            'id_inventario' => 'Id Inventario',
            'numero_caja' => 'numero_caja',
            'numero_guia' => 'numero_guia',
            'linea_duplicada' => 'linea_duplicada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPacking()
    {
        return $this->hasOne(PackingPedido::className(), ['id_packing' => 'id_packing']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
