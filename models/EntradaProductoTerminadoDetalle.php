<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada_producto_terminado_detalle".
 *
 * @property int $id_detalle
 * @property int $id_entrada
 * @property int $id_inventario
 * @property string $fecha_vencimiento
 * @property int $actualizar_precio
 * @property double $porcentaje_iva
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $total_iva
 * @property int $subtotal
 * @property int $total_entrada
 *
 * @property EntradaProductoTerminado $entrada
 * @property InventarioProductos $inventario
 */
class EntradaProductoTerminadoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada_producto_terminado_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrada', 'id_inventario', 'actualizar_precio', 'cantidad', 'valor_unitario', 'total_iva', 'subtotal', 'total_entrada','numero_lote'], 'integer'],
            [['fecha_vencimiento'], 'safe'],
            [['porcentaje_iva'], 'number'],
            ['codigo_producto', 'string'],
            [['id_entrada'], 'exist', 'skipOnError' => true, 'targetClass' => EntradaProductoTerminado::className(), 'targetAttribute' => ['id_entrada' => 'id_entrada']],
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
            'id_entrada' => 'Id Entrada',
            'id_inventario' => 'Id Inventario',
            'fecha_vencimiento' => 'Fecha Vencimiento',
            'actualizar_precio' => 'Actualizar Precio',
            'porcentaje_iva' => 'Porcentaje Iva',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'total_iva' => 'Total Iva',
            'subtotal' => 'Subtotal',
            'total_entrada' => 'Total Entrada',
            'codigo_producto' => 'Codigo producto:',
            'numero_lote' => 'numero_lote:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntrada()
    {
        return $this->hasOne(EntradaProductoTerminado::className(), ['id_entrada' => 'id_entrada']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
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
