<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_entrega_kits_detalles".
 *
 * @property int $id_detalle
 * @property int $id_orden_entrega
 * @property int $id_detalle_entrega
 * @property int $cantidad_producto
 *
 * @property OrdenEntregaKits $ordenEntrega
 * @property EntregaSolicitudKitsDetalle $detalleEntrega
 */
class OrdenEntregaKitsDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_entrega_kits_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_entrega', 'id_detalle_entrega', 'cantidad_producto'], 'integer'],
            [['id_orden_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenEntregaKits::className(), 'targetAttribute' => ['id_orden_entrega' => 'id_orden_entrega']],
            [['id_detalle_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => EntregaSolicitudKitsDetalle::className(), 'targetAttribute' => ['id_detalle_entrega' => 'id_detalle_entrega']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_orden_entrega' => 'Id Orden Entrega',
            'id_detalle_entrega' => 'Id Detalle Entrega',
            'cantidad_producto' => 'Cantidad Producto',
        ];
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
    public function getDetalleEntrega()
    {
        return $this->hasOne(EntregaSolicitudKitsDetalle::className(), ['id_detalle_entrega' => 'id_detalle_entrega']);
    }
}
