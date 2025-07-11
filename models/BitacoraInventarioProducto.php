<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bitacora_inventario_producto".
 *
 * @property int $id_bitacora_producto
 * @property int $id_inventario
 * @property int $cantidad
 * @property string $fecha_proceso
 * @property string $fecha_hora_registro
 * @property string $user_name
 * @property int $id_orden_entrega
 * @property int $id_orden_produccion
 * @property int $entrada_salida
 * @property string $nota
 * @property int $id_pedido
 *
 * @property InventarioProductos $inventario
 * @property OrdenEntregaKits $ordenEntrega
 * @property OrdenProduccion $ordenProduccion
 * @property Pedidos $pedido
 */
class BitacoraInventarioProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bitacora_inventario_producto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'cantidad', 'id_orden_entrega', 'id_orden_produccion', 'entrada_salida', 'id_pedido'], 'integer'],
            [['fecha_proceso', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['nota'], 'string', 'max' => 100],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_orden_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenEntregaKits::className(), 'targetAttribute' => ['id_orden_entrega' => 'id_orden_entrega']],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['id_pedido' => 'id_pedido']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_bitacora_producto' => 'Id Bitacora Producto',
            'id_inventario' => 'Id Inventario',
            'cantidad' => 'Cantidad',
            'fecha_proceso' => 'Fecha Proceso',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'user_name' => 'User Name',
            'id_orden_entrega' => 'Id Orden Entrega',
            'id_orden_produccion' => 'Id Orden Produccion',
            'entrada_salida' => 'Entrada Salida',
            'nota' => 'Nota',
            'id_pedido' => 'Id Pedido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenEntrega()
    {
        return $this->hasOne(OrdenEntregaKits::className(), ['id_orden_entrega' => 'id_orden_entrega']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedidos::className(), ['id_pedido' => 'id_pedido']);
    }
}
