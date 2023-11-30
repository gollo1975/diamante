<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacenamiento_producto_entrada".
 *
 * @property int $id_almacenamiento
 * @property int $id_entrada
 * @property int $id_documento
 * @property int $numero_soporte
 * @property int $id_inventario
 * @property string $codigo_producto
 * @property string $nombre_producto
 * @property int $unidad_producidas
 * @property int $unidades_almacenadas
 * @property int $unidades_faltantes
 * @property string $fecha_almacenamiento
 * @property string $user_name
 *
 * @property EntradaProductoTerminado $entrada
 * @property DocumentoAlmacenamiento $documento
 * @property InventarioProductos $inventario
 */
class AlmacenamientoProductoEntrada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'almacenamiento_producto_entrada';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrada', 'id_documento', 'numero_soporte', 'id_inventario', 'unidad_producidas', 'unidades_almacenadas', 'unidades_faltantes'], 'integer'],
            [['fecha_almacenamiento'], 'safe'],
            [['codigo_producto', 'user_name'], 'string', 'max' => 15],
            [['nombre_producto'], 'string', 'max' => 40],
            [['id_entrada'], 'exist', 'skipOnError' => true, 'targetClass' => EntradaProductoTerminado::className(), 'targetAttribute' => ['id_entrada' => 'id_entrada']],
            [['id_documento'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoAlmacenamiento::className(), 'targetAttribute' => ['id_documento' => 'id_documento']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_almacenamiento' => 'Id Almacenamiento',
            'id_entrada' => 'Id Entrada',
            'id_documento' => 'Id Documento',
            'numero_soporte' => 'Numero Soporte',
            'id_inventario' => 'Id Inventario',
            'codigo_producto' => 'Codigo Producto',
            'nombre_producto' => 'Nombre Producto',
            'unidad_producidas' => 'Unidad Producidas',
            'unidades_almacenadas' => 'Unidades Almacenadas',
            'unidades_faltantes' => 'Unidades Faltantes',
            'fecha_almacenamiento' => 'Fecha Almacenamiento',
            'user_name' => 'User Name',
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
    public function getDocumento()
    {
        return $this->hasOne(DocumentoAlmacenamiento::className(), ['id_documento' => 'id_documento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
