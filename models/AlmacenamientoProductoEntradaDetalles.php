<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacenamiento_producto_entrada_detalles".
 *
 * @property int $id
 * @property int $id_almacenamiento
 * @property int $id_rack
 * @property int $id_posicion
 * @property int $id_piso
 * @property int $id_entrada
 * @property int $id_inventario
 * @property int $cantidad
 * @property string $codigo_producto
 * @property string $producto
 * @property int $numero_lote
 * @property string $fecha_almacenamiento
 *
 * @property AlmacenamientoProductoEntrada $almacenamiento
 * @property TipoRack $rack
 * @property Posiciones $posicion
 * @property Pisos $piso
 * @property EntradaProductoTerminado $entrada
 * @property InventarioProductos $inventario
 */
class AlmacenamientoProductoEntradaDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'almacenamiento_producto_entrada_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_almacenamiento', 'id_rack', 'id_posicion', 'id_piso', 'id_entrada', 'id_inventario', 'cantidad', 'numero_lote'], 'integer'],
            [['fecha_almacenamiento'], 'safe'],
            [['codigo_producto'], 'string', 'max' => 15],
            [['producto'], 'string', 'max' => 40],
            [['id_almacenamiento'], 'exist', 'skipOnError' => true, 'targetClass' => AlmacenamientoProductoEntrada::className(), 'targetAttribute' => ['id_almacenamiento' => 'id_almacenamiento']],
            [['id_rack'], 'exist', 'skipOnError' => true, 'targetClass' => TipoRack::className(), 'targetAttribute' => ['id_rack' => 'id_rack']],
            [['id_posicion'], 'exist', 'skipOnError' => true, 'targetClass' => Posiciones::className(), 'targetAttribute' => ['id_posicion' => 'id_posicion']],
            [['id_piso'], 'exist', 'skipOnError' => true, 'targetClass' => Pisos::className(), 'targetAttribute' => ['id_piso' => 'id_piso']],
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
            'id' => 'ID',
            'id_almacenamiento' => 'Id Almacenamiento',
            'id_rack' => 'Id Rack',
            'id_posicion' => 'Id Posicion',
            'id_piso' => 'Id Piso',
            'id_entrada' => 'Id Entrada',
            'id_inventario' => 'Id Inventario',
            'cantidad' => 'Cantidad',
            'codigo_producto' => 'Codigo Producto',
            'producto' => 'Producto',
            'numero_lote' => 'Numero Lote',
            'fecha_almacenamiento' => 'Fecha Almacenamiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenamiento()
    {
        return $this->hasOne(AlmacenamientoProductoEntrada::className(), ['id_almacenamiento' => 'id_almacenamiento']);
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
}
