<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud_compra_detalles".
 *
 * @property int $id
 * @property int $id_items
 * @property int $id_solicitud_compra
 * @property double $porcentaje_iva
 * @property int $cantidad
 * @property int $valor
 * @property int $valor_iva
 * @property int $subtotal
 * @property int $total_solicitud
 *
 * @property Items $items
 * @property SolicitudCompra $solicitudCompra
 */
class SolicitudCompraDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_compra_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_items', 'id_solicitud_compra', 'cantidad', 'valor', 'valor_iva', 'subtotal', 'total_solicitud'], 'integer'],
            [['id_solicitud_compra'], 'required'],
            [['porcentaje_iva'], 'number'],
            [['id_items'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['id_items' => 'id_items']],
            [['id_solicitud_compra'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudCompra::className(), 'targetAttribute' => ['id_solicitud_compra' => 'id_solicitud_compra']],
            [['id_solicitud_compra'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudCompra::className(), 'targetAttribute' => ['id_solicitud_compra' => 'id_solicitud_compra']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_items' => 'Id Items',
            'id_solicitud_compra' => 'Id Solicitud Compra',
            'porcentaje_iva' => 'Porcentaje Iva',
            'cantidad' => 'Cantidad',
            'valor' => 'Valor',
            'valor_iva' => 'Valor Iva',
            'subtotal' => 'Subtotal',
            'total_solicitud' => 'Total Solicitud',
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
    public function getSolicitudCompra()
    {
        return $this->hasOne(SolicitudCompra::className(), ['id_solicitud_compra' => 'id_solicitud_compra']);
    }
}
