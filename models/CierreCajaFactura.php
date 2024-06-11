<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cierre_carja_factura".
 *
 * @property int $id_detalle
 * @property int $id_recibo
 * @property int $id_factura
 * @property int $valor_pago
 * @property string $user_name
 * @property string $fecha_hora_carga
 *
 * @property ReciboCajaPuntoVenta $recibo
 * @property FacturaVentaPunto $factura
 */
class CierreCajaFactura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cierre_caja_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cierre', 'id_factura', 'valor_pago','id_recibo'], 'integer'],
            [['fecha_hora_carga'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_cierre'], 'exist', 'skipOnError' => true, 'targetClass' => CierreCaja::className(), 'targetAttribute' => ['id_cierre' => 'id_cierre']],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVentaPunto::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
            [['id_recibo'], 'exist', 'skipOnError' => true, 'targetClass' => ReciboCajaPuntoVenta::className(), 'targetAttribute' => ['id_recibo' => 'id_recibo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_cierre' => 'id_cierre',
            'id_factura' => 'Id Factura',
            'valor_pago' => 'Valor Pago',
            'user_name' => 'User Name',
            'fecha_hora_carga' => 'Fecha Hora Carga',
            'id_recibo' => 'id_recibo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCierreCaja()
    {
        return $this->hasOne(CierreCaja::className(), ['id_cierre' => 'id_cierre']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecibo()
    {
        return $this->hasOne(ReciboCajaPuntoVenta::className(), ['id_recibo' => 'id_recibo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(FacturaVentaPunto::className(), ['id_factura' => 'id_factura']);
    }
}
