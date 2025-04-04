<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacenamiento_producto".
 *
 * @property int $id_almacenamiento
 * @property int $id_orden_produccion
 * @property int $id_documento
 * @property int $id_rack
 * @property string $codigo_producto
 * @property string $nombre_producto
 * @property int $unidades_producidas
 * @property int $unidades_almacenadas
 * @property int $unidades_faltantes
 * @property string $fecha_almacenamiento
 * @property string $user_name
 *
 * @property OrdenProduccion $ordenProduccion
 * @property DocumentoAlmacenamiento $documento
 * @property TipoRack $rack
 */
class AlmacenamientoProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'almacenamiento_producto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'id_documento',  'unidades_producidas', 'unidades_almacenadas', 'unidades_faltantes','id_inventario','id_devolucion'], 'integer'],
            [['fecha_almacenamiento','fecha_vencimiento'], 'safe'],
            [['codigo_producto', 'user_name','numero_lote'], 'string', 'max' => 15],
            [['nombre_producto'], 'string', 'max' => 40],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_documento'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoAlmacenamiento::className(), 'targetAttribute' => ['id_documento' => 'id_documento']],
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
            'id_almacenamiento' => 'Id',
            'id_orden_produccion' => 'Orden Produccion',
            'id_documento' => 'Documento',
            'numero_lote' => 'Numero lote',
            'codigo_producto' => 'Codigo Producto',
            'nombre_producto' => 'Nombre Producto',
            'unidades_producidas' => 'Unidades Producidas',
            'unidades_almacenadas' => 'Unidades Almacenadas',
            'unidades_faltantes' => 'Unidades Faltantes',
            'fecha_almacenamiento' => 'Fecha Almacenamiento',
            'user_name' => 'User Name',
            'id_inventario' => 'id_inventario',
            'fecha_vencimiento' => 'fecha_vencimiento',
            'id_devolucion' => 'id_devolucion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
    }
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumento()
    {
        return $this->hasOne(DocumentoAlmacenamiento::className(), ['id_documento' => 'id_documento']);
    }
    
      /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucionProducto()
    {
        return $this->hasOne(DevolucionProductos::className(), ['id_devolucion' => 'id_devolucion']);
    }

}
