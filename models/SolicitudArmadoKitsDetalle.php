<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud_armado_kits_detalle".
 *
 * @property int $id_detalle
 * @property int $id_solicitud_armado
 * @property int $id_inventario
 * @property int $cantidad_solicitada
 *
 * @property SolicitudArmadoKits $solicitudArmado
 * @property InventarioProductos $inventario
 */
class SolicitudArmadoKitsDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_armado_kits_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_solicitud_armado', 'id_inventario', 'cantidad_solicitada'], 'integer'],
            [['id_solicitud_armado'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudArmadoKits::className(), 'targetAttribute' => ['id_solicitud_armado' => 'id_solicitud_armado']],
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
            'id_solicitud_armado' => 'Id Solicitud Armado',
            'id_inventario' => 'Id Inventario',
            'cantidad_solicitada' => 'Cantidad Solicitada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudArmado()
    {
        return $this->hasOne(SolicitudArmadoKits::className(), ['id_solicitud_armado' => 'id_solicitud_armado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
