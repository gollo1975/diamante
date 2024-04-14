<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura_venta_punto".
 *
 * @property int $id_factura
 * @property int $id_cliente
 * @property int $id_tipo_factura
 * @property int $id_agente
 * @property int $id_tipo_venta
 * @property int $numero_factura
 * @property string $nit_cedula
 * @property int $dv
 * @property string $cliente
 * @property string $direccion
 * @property string $telefono_cliente
 * @property string $numero_resolucion
 * @property string $desde
 * @property string $hasta
 * @property string $consecutivo
 * @property string $fecha_inicio
 * @property string $fecha_vencimiento
 * @property string $fecha_generada
 * @property string $fecha_enviada
 * @property int $valor_bruto
 * @property int $descuento
 * @property int $subtotal_factura
 * @property int $impuesto
 * @property int $total_factura
 * @property double $porcentaje_iva
 * @property double $porcentaje_rete_iva
 * @property double $porcentaje_rete_fuente
 * @property int $valor_retencion
 * @property int $valor_reteiva
 * @property double $porcentaje_descuento
 * @property int $saldo_factura
 * @property int $forma_pago
 * @property int $plazo_pago
 * @property int $autorizado
 * @property string $user_name
 * @property string $observacion
 * @property string $fecha_editada
 * @property string $user_name_editado
 * @property int $estado_factura
 * @property int $dias_mora
 * @property int $valor_intereses_mora
 * @property int $iva_intereses_mora
 * @property int $subtotal_interes_masiva
 * @property double $porcentaje_mora
 * @property int $id_punto
 *
 * @property Clientes $cliente0
 * @property TipoFacturaVenta $tipoFactura
 * @property AgentesComerciales $agente
 * @property TipoVenta $tipoVenta
 * @property PuntoVenta $punto
 * @property FacturaVentaPuntoDetalle[] $facturaVentaPuntoDetalles
 */
class FacturaVentaPunto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factura_venta_punto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_factura', 'id_cliente', 'id_tipo_factura', 'id_agente', 'id_tipo_venta', 'numero_factura', 'dv', 'valor_bruto', 'descuento', 'subtotal_factura', 'impuesto', 
                'total_factura', 'valor_retencion', 'valor_reteiva', 'saldo_factura', 'forma_pago', 'plazo_pago', 'autorizado', 'estado_factura', 'dias_mora',
                'valor_intereses_mora', 'iva_intereses_mora', 'subtotal_interes_masiva', 'id_punto'], 'integer'],
            [['desde', 'hasta', 'fecha_inicio', 'fecha_vencimiento', 'fecha_generada', 'fecha_enviada', 'fecha_editada'], 'safe'],
            [['porcentaje_iva', 'porcentaje_rete_iva', 'porcentaje_rete_fuente', 'porcentaje_descuento', 'porcentaje_mora'], 'number'],
            [['nit_cedula', 'user_name', 'user_name_editado'], 'string', 'max' => 15],
            [['cliente', 'direccion'], 'string', 'max' => 50],
            [['telefono_cliente'], 'string', 'max' => 12],
            [['numero_resolucion'], 'string', 'max' => 30],
            [['consecutivo'], 'string', 'max' => 3],
            [['observacion'], 'string', 'max' => 200],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_tipo_factura'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFacturaVenta::className(), 'targetAttribute' => ['id_tipo_factura' => 'id_tipo_factura']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
            [['id_tipo_venta'], 'exist', 'skipOnError' => true, 'targetClass' => TipoVenta::className(), 'targetAttribute' => ['id_tipo_venta' => 'id_tipo_venta']],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_factura' => 'Id:',
            'id_cliente' => 'Cliente:',
            'id_tipo_factura' => 'Tipo factura:',
            'id_agente' => 'Vendedor:',
            'id_tipo_venta' => 'Tipo venta:',
            'numero_factura' => 'Numero factura',
            'nit_cedula' => 'Nit/Cedula:',
            'dv' => 'Dv:',
            'cliente' => 'Cliente:',
            'direccion' => 'Direccion:',
            'telefono_cliente' => 'Telefono:',
            'numero_resolucion' => 'Resolucion:',
            'desde' => 'Desde:',
            'hasta' => 'Hasta:',
            'consecutivo' => 'Consecutivo:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_vencimiento' => 'Fecha vencimiento:',
            'fecha_generada' => 'Fecha generada:',
            'fecha_enviada' => 'Fecha enviada:',
            'valor_bruto' => 'Valor bruto:',
            'descuento' => 'Descuento:',
            'subtotal_factura' => 'Subtotal:',
            'impuesto' => 'Impuesto:',
            'total_factura' => 'Total factura:',
            'porcentaje_iva' => 'Porcentaje Iva:',
            'porcentaje_rete_iva' => 'Porcentaje rete Iva:',
            'porcentaje_rete_fuente' => 'Porcentaje rete fuente',
            'valor_retencion' => 'Valor retencion:',
            'valor_reteiva' => 'Valor reteiva:',
            'porcentaje_descuento' => '% Descuento:',
            'saldo_factura' => 'Saldo:',
            'forma_pago' => 'Forma pago:',
            'plazo_pago' => 'Plazo:',
            'autorizado' => 'Autorizado:',
            'user_name' => 'User Name:',
            'observacion' => 'Observacion:',
            'fecha_editada' => 'Fecha Editada',
            'user_name_editado' => 'User Name Editado',
            'estado_factura' => 'Estado:',
            'dias_mora' => 'Dias mora',
            'valor_intereses_mora' => 'Valor Intereses Mora',
            'iva_intereses_mora' => 'Iva Intereses Mora',
            'subtotal_interes_masiva' => 'Subtotal Interes Masiva',
            'porcentaje_mora' => 'Porcentaje Mora',
            'id_punto' => 'Punto de venta:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteFactura()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoFactura()
    {
        return $this->hasOne(TipoFacturaVenta::className(), ['id_tipo_factura' => 'id_tipo_factura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgente()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoVenta()
    {
        return $this->hasOne(TipoVenta::className(), ['id_tipo_venta' => 'id_tipo_venta']);
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
    public function getFacturaVentaPuntoDetalles()
    {
        return $this->hasMany(FacturaVentaPuntoDetalle::className(), ['id_factura' => 'id_factura']);
    }
}
