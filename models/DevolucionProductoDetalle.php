<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "devolucion_producto_detalle".
 *
 * @property int $id_detalle
 * @property int $id_inventario
 * @property int $id_devolucion
 * @property int $id_tipo_devolucion
 * @property int $codigo_producto
 * @property string $nombre_producto
 * @property int $cantidad
 * @property string $fecha_registro
 *
 * @property InventarioProductos $inventario
 * @property DevolucionProductos $devolucion
 * @property TipoDevolucionProductos $tipoDevolucion
 */
class DevolucionProductoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devolucion_producto_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'id_devolucion', 'id_tipo_devolucion', 'codigo_producto', 'cantidad','cantidad_averias'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['nombre_producto'], 'string', 'max' => 40],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_devolucion'], 'exist', 'skipOnError' => true, 'targetClass' => DevolucionProductos::className(), 'targetAttribute' => ['id_devolucion' => 'id_devolucion']],
            [['id_tipo_devolucion'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDevolucionProductos::className(), 'targetAttribute' => ['id_tipo_devolucion' => 'id_tipo_devolucion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_inventario' => 'Id Inventario',
            'id_devolucion' => 'Id Devolucion',
            'id_tipo_devolucion' => 'Id Tipo Devolucion',
            'codigo_producto' => 'Codigo Producto',
            'nombre_producto' => 'Nombre Producto',
            'cantidad' => 'Cantidad',
            'fecha_registro' => 'Fecha Registro',
            'cantidad_averias' => 'cantidad_averias',
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
    public function getDevolucion()
    {
        return $this->hasOne(DevolucionProductos::className(), ['id_devolucion' => 'id_devolucion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDevolucion()
    {
        return $this->hasOne(TipoDevolucionProductos::className(), ['id_tipo_devolucion' => 'id_tipo_devolucion']);
    }
}
