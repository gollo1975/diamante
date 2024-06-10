<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recibo_caja_punto_venta".
 *
 * @property int $id_recibo
 * @property int $id_remision
 * @property int $id_factura
 * @property int $id_tipo
 * @property int $id_punto
 * @property string $fecha_recibo
 * @property int $numero_recibo
 * @property int $valor_abono
 * @property int $valor_saldo
 * @property string $user_name
 *
 * @property Remisiones $remision
 * @property FacturaVentaPunto $factura
 * @property TipoReciboCaja $tipo
 * @property PuntoVenta $punto
 */
class ReciboCajaPuntoVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recibo_caja_punto_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_remision', 'id_factura', 'id_tipo', 'id_punto', 'numero_recibo', 'valor_abono', 'valor_saldo','id_forma_pago'], 'integer'],
            [['fecha_recibo'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_banco'],'string', 'max' => 10],
            [['numero_transacion'], 'string'],
            [['id_remision'], 'exist', 'skipOnError' => true, 'targetClass' => Remisiones::className(), 'targetAttribute' => ['id_remision' => 'id_remision']],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVentaPunto::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
            [['id_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoReciboCaja::className(), 'targetAttribute' => ['id_tipo' => 'id_tipo']],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
            [['codigo_banco'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadBancarias::className(), 'targetAttribute' => ['codigo_banco' => 'codigo_banco']],
            [['id_forma_pago'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['id_forma_pago' => 'id_forma_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_recibo' => 'Id Recibo',
            'id_remision' => 'Id Remision',
            'id_factura' => 'Id Factura',
            'id_tipo' => 'Id Tipo',
            'id_punto' => 'Id Punto',
            'fecha_recibo' => 'Fecha Recibo',
            'numero_recibo' => 'Numero Recibo',
            'valor_abono' => 'Valor Abono',
            'valor_saldo' => 'Valor Saldo',
            'user_name' => 'User Name',
            'id_forma_pago' => 'id_forma_pago',
            'codigo_banco' => 'codigo_banco',
            'numero_transacion' => 'numero_transacion',
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
    public function getFactura()
    {
        return $this->hasOne(FacturaVentaPunto::className(), ['id_factura' => 'id_factura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(TipoReciboCaja::className(), ['id_tipo' => 'id_tipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPunto()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormaPago()
    {
        return $this->hasOne(FormaPago::className(), ['id_forma_pago' => 'id_forma_pago']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoBanco()
    {
        return $this->hasOne(EntidadBancarias::className(), ['codigo_banco' => 'codigo_banco']);
    }
}
