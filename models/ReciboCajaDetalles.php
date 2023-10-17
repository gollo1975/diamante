<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recibo_caja_detalles".
 *
 * @property int $id_detalle
 * @property int $id_recibo
 * @property int $id_factura
 * @property int $numero_factura
 * @property int $retencion
 * @property int $reteiva
 * @property int $saldo_factura
 * @property int $abono_factura
 * @property string $fecha_registro
 *
 * @property ReciboCaja $recibo
 * @property FacturaVenta $factura
 */
class ReciboCajaDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recibo_caja_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_recibo', 'id_factura', 'numero_factura', 'retencion', 'reteiva', 'saldo_factura', 'abono_factura'], 'integer'],
            [['numero_factura'], 'required'],
            [['fecha_registro','fecha_pago'], 'safe'],
            [['id_recibo'], 'exist', 'skipOnError' => true, 'targetClass' => ReciboCaja::className(), 'targetAttribute' => ['id_recibo' => 'id_recibo']],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVenta::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_recibo' => 'Id Recibo',
            'id_factura' => 'Id Factura',
            'numero_factura' => 'Numero Factura',
            'retencion' => 'Retencion',
            'reteiva' => 'Reteiva',
            'saldo_factura' => 'Saldo Factura',
            'abono_factura' => 'Abono Factura',
            'fecha_registro' => 'Fecha Registro',
            'fecha_pago' => 'Fecha pago',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecibo()
    {
        return $this->hasOne(ReciboCaja::className(), ['id_recibo' => 'id_recibo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacturaRecibo()
    {
        return $this->hasOne(FacturaVenta::className(), ['id_factura' => 'id_factura']);
    }
}
