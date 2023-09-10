<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_compra_detalle".
 *
 * @property int $id_detalle
 * @property int $id_items
 * @property int $id_orden_compra
 * @property double $porcentaje
 * @property int $cantidad
 * @property int $valor
 * @property int $valor_iva
 * @property int $subtotal
 * @property int $total_orden
 *
 * @property Items $items
 * @property OrdenCompra $ordenCompra
 */
class OrdenCompraDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_compra_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_items', 'id_orden_compra', 'cantidad', 'valor', 'valor_iva', 'subtotal', 'total_orden'], 'integer'],
            [['porcentaje'], 'number'],
            [['id_items'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['id_items' => 'id_items']],
            [['id_orden_compra'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenCompra::className(), 'targetAttribute' => ['id_orden_compra' => 'id_orden_compra']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_items' => 'Id Items',
            'id_orden_compra' => 'Id Orden Compra',
            'porcentaje' => 'Porcentaje',
            'cantidad' => 'Cantidad',
            'valor' => 'Valor',
            'valor_iva' => 'Valor Iva',
            'subtotal' => 'Subtotal',
            'total_orden' => 'Total Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasOne(Items::className(), ['id_items' => 'id_items']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenCompra()
    {
        return $this->hasOne(OrdenCompra::className(), ['id_orden_compra' => 'id_orden_compra']);
    }
}
