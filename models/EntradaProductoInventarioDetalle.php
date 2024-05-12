<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada_producto_inventario_detalle".
 *
 * @property int $id_detalle
 * @property int $id_entrada
 * @property int $id_inventario
 * @property string $codigo_producto
 * @property string $fecha_vencimiento
 * @property int $actualizar_precio
 * @property double $porcentaje_iva
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $total_iva
 * @property int $subtotal
 * @property int $total_entrada
 *
 * @property EntradaProductosInventario $entrada
 * @property InventarioPuntoVenta $inventario
 */
class EntradaProductoInventarioDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada_producto_inventario_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrada', 'id_inventario', 'actualizar_precio', 'cantidad', 'valor_unitario', 'total_iva', 'subtotal', 'total_entrada'], 'integer'],
            [['fecha_vencimiento'], 'safe'],
            [['porcentaje_iva'], 'number'],
            [['codigo_producto'], 'string', 'max' => 15],
            [['id_entrada'], 'exist', 'skipOnError' => true, 'targetClass' => EntradaProductosInventario::className(), 'targetAttribute' => ['id_entrada' => 'id_entrada']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_entrada' => 'Id Entrada',
            'id_inventario' => 'Id Inventario',
            'codigo_producto' => 'Codigo Producto',
            'fecha_vencimiento' => 'Fecha Vencimiento',
            'actualizar_precio' => 'Actualizar Precio',
            'porcentaje_iva' => 'Porcentaje Iva',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'total_iva' => 'Total Iva',
            'subtotal' => 'Subtotal',
            'total_entrada' => 'Total Entrada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntrada()
    {
        return $this->hasOne(EntradaProductosInventario::className(), ['id_entrada' => 'id_entrada']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioPuntoVenta::className(), ['id_inventario' => 'id_inventario']);
    }
    
     public function getActualizarPrecio() {
        if($this->actualizar_precio == 0){
            $actualizarprecio = 'NO';
        }else{
            $actualizarprecio = 'SI';
        }
        return $actualizarprecio;
    }
}
