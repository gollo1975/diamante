<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacenamiento_producto_detalles".
 *
 * @property int $id
 * @property int $id_almacenamiento
 * @property int $id_rack
 * @property int $id_posicion
 * @property int $id_piso
 * @property int $cantidad
 * @property string $codigo_producto
 * @property string $producto
 * @property int $numero_lote
 *
 * @property AlmacenamientoProducto $almacenamiento
 * @property TipoRack $rack
 * @property Posiciones $posicion
 * @property Pisos $piso
 */
class AlmacenamientoProductoDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'almacenamiento_producto_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_almacenamiento', 'id_rack', 'id_posicion', 'id_piso', 'cantidad', 'id_orden_produccion','id_devolucion'], 'integer'],
            [['codigo_producto','numero_lote'], 'string', 'max' => 15],
            [['producto'], 'string', 'max' => 40],
            [['fecha_almacenamiento','fecha_proceso_lote','fecha_vencimiento'], 'safe'],
            [['id_almacenamiento'], 'exist', 'skipOnError' => true, 'targetClass' => AlmacenamientoProducto::className(), 'targetAttribute' => ['id_almacenamiento' => 'id_almacenamiento']],
            [['id_rack'], 'exist', 'skipOnError' => true, 'targetClass' => TipoRack::className(), 'targetAttribute' => ['id_rack' => 'id_rack']],
            [['id_posicion'], 'exist', 'skipOnError' => true, 'targetClass' => Posiciones::className(), 'targetAttribute' => ['id_posicion' => 'id_posicion']],
            [['id_piso'], 'exist', 'skipOnError' => true, 'targetClass' => Pisos::className(), 'targetAttribute' => ['id_piso' => 'id_piso']],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_devolucion'], 'exist', 'skipOnError' => true, 'targetClass' => DevolucionProductos::className(), 'targetAttribute' => ['id_devolucion' => 'id_devolucion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_almacenamiento' => 'Id Almacenamiento',
            'id_rack' => 'Rack:',
            'id_posicion' => 'Posicion:',
            'id_piso' => 'Piso:',
            'cantidad' => 'Cantidad',
            'codigo_producto' => 'Codigo Producto',
            'producto' => 'Producto',
            'numero_lote' => 'Numero Lote',
            'id_orden_produccion' => 'Orden produccion:',
            'fecha_almacenamiento' => 'Fecha almacenamiento',
            'id_inventario' => 'id_inventario',
            'fecha_proceso_lote' => 'Fecha proceso lote',
            'fecha_vencimiento' => 'fecha_vencimiento',
            'id_devolucion' => 'id_devolucion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenamiento()
    {
        return $this->hasOne(AlmacenamientoProducto::className(), ['id_almacenamiento' => 'id_almacenamiento']);
    }
    
     public function getDevolucionProducto()
    {
        return $this->hasOne(DevolucionProductos::className(), ['id_devolucion' => 'id_devolucion']);
    }
    
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
    }
     public function getInventarioProducto()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRack()
    {
        return $this->hasOne(TipoRack::className(), ['id_rack' => 'id_rack']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosicion()
    {
        return $this->hasOne(Posiciones::className(), ['id_posicion' => 'id_posicion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPiso()
    {
        return $this->hasOne(Pisos::className(), ['id_piso' => 'id_piso']);
    }
}
