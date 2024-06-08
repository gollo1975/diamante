<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remision_detalles".
 *
 * @property int $id_detalle
 * @property int $id_remision
 * @property int $id_inventario
 * @property int $codigo_producto
 * @property string $producto
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $subtotal
 * @property double $porcentaje_descuento
 * @property int $valor_descuento
 * @property double $porcentaje_iva
 * @property int $impuesto
 * @property int $total_linea
 *
 * @property Remisiones $remision
 * @property InventarioPuntoVenta $inventario
 */
class RemisionDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remision_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_remision', 'id_inventario', 'codigo_producto', 'cantidad', 'valor_unitario', 'subtotal', 'valor_descuento', 'impuesto', 'total_linea','id_punto'], 'integer'],
            [['porcentaje_descuento', 'porcentaje_iva'], 'number'],
            [['producto'], 'string', 'max' => 40],
            ['fecha_inicio', 'safe'],
            [['id_remision'], 'exist', 'skipOnError' => true, 'targetClass' => Remisiones::className(), 'targetAttribute' => ['id_remision' => 'id_remision']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_remision' => 'Id Remision',
            'id_inventario' => 'Id Inventario',
            'codigo_producto' => 'Codigo Producto',
            'producto' => 'Producto',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'subtotal' => 'Subtotal',
            'porcentaje_descuento' => 'Porcentaje Descuento',
            'valor_descuento' => 'Valor Descuento',
            'porcentaje_iva' => 'Porcentaje Iva',
            'impuesto' => 'Impuesto',
            'total_linea' => 'Total Linea',
            'fecha_inicio' => 'fecha_inicio',
            'id_punto' => 'id_punto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemision()
    {
        return $this->hasOne(Remisiones::className(), ['id_remision' => 'id_remision']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPuntoVenta()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioPuntoVenta::className(), ['id_inventario' => 'id_inventario']);
    }
}
