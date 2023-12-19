<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura_venta_detalle".
 *
 * @property int $id_detalle
 * @property int $id_factura
 * @property int $id_inventario
 * @property int $codigo_producto
 * @property string $producto
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $subtotal
 * @property double $porcentaje_descuento
 * @property int $valor_descuento
 * @property int $impuesto
 * @property int $total_linea
 *
 * @property FacturaVenta $factura
 * @property InventarioProductos $inventario
 */
class FacturaVentaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factura_venta_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_factura', 'id_inventario', 'codigo_producto', 'cantidad', 'valor_unitario', 'subtotal', 'valor_descuento', 'impuesto', 'total_linea'], 'integer'],
            [['porcentaje_descuento','porcentaje_iva'], 'number'],
            [['producto'], 'string', 'max' => 40],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVenta::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
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
            'id_factura' => 'Id Factura',
            'id_inventario' => 'Id Inventario',
            'codigo_producto' => 'Codigo Producto',
            'producto' => 'Producto',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'subtotal' => 'Subtotal',
            'porcentaje_descuento' => 'Porcentaje Descuento',
            'valor_descuento' => 'Valor Descuento',
            'impuesto' => 'Impuesto',
            'total_linea' => 'Total Linea',
            'porcentaje_iva' => 'porcentaje_iva',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(FacturaVenta::className(), ['id_factura' => 'id_factura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
