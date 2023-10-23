<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nota_credito_detalle".
 *
 * @property int $id_detalle
 * @property int $id_nota
 * @property int $id_inventario
 * @property int $codigo_producto
 * @property string $producto
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $subtotal
 * @property int $impuesto
 * @property int $total_linea
 *
 * @property NotaCredito $nota
 * @property InventarioProductos $inventario
 */
class NotaCreditoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nota_credito_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_nota', 'id_inventario', 'codigo_producto', 'cantidad', 'valor_unitario', 'subtotal', 'impuesto', 'total_linea'], 'integer'],
            [['producto'], 'string', 'max' => 40],
            [['id_nota'], 'exist', 'skipOnError' => true, 'targetClass' => NotaCredito::className(), 'targetAttribute' => ['id_nota' => 'id_nota']],
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
            'id_nota' => 'Id Nota',
            'id_inventario' => 'Id Inventario',
            'codigo_producto' => 'Codigo Producto',
            'producto' => 'Producto',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'subtotal' => 'Subtotal',
            'impuesto' => 'Impuesto',
            'total_linea' => 'Total Linea',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNota()
    {
        return $this->hasOne(NotaCredito::className(), ['id_nota' => 'id_nota']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
