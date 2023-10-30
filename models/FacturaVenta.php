<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura_venta".
 *
 * @property int $id_factura
 * @property int $id_pedido
 * @property int $id_cliente
 * @property int $id_tipo_factura
 * @property int $numero_factura
 * @property string $nit_cedula
 * @property int $dv
 * @property string $cliente
 * @property string $numero_resolucion
 * @property string $desde
 * @property string $hasta
 * @property string $consecutivo
 * @property string $fecha_inicio
 * @property string $fecha_vencimiento
 * @property string $fecha_generada
 * @property string $fecha_enviada
 * @property int $subtotal_factura
 * @property int $descuento
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
 *
 * @property Pedidos $pedido
 * @property Clientes $cliente0
 * @property TipoFacturaVenta $tipoFactura
 */
class FacturaVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factura_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pedido', 'user_name'], 'required'],
            [['id_pedido', 'id_cliente', 'id_tipo_factura', 'numero_factura', 'dv', 'subtotal_factura', 'descuento', 'impuesto', 'total_factura', 'valor_retencion', 
                'valor_reteiva', 'saldo_factura', 'forma_pago', 'plazo_pago', 'autorizado','valor_bruto','id_agente','estado_factura','dias_mora','valor_intereses_mora',
                'iva_intereses_mora', 'subtotal_interes_masiva'], 'integer'],
            [['desde', 'hasta', 'fecha_inicio', 'fecha_vencimiento', 'fecha_generada', 'fecha_enviada','fecha_editada'], 'safe'],
            [['porcentaje_iva', 'porcentaje_rete_iva', 'porcentaje_rete_fuente', 'porcentaje_descuento','porcentaje_mora'], 'number'],
            [['nit_cedula', 'user_name','telefono_cliente','user_name_editado'], 'string', 'max' => 15],
            [['cliente','direccion'], 'string', 'max' => 50],
            [['numero_resolucion'], 'string', 'max' => 30],
            [['consecutivo'], 'string', 'max' => 3],
            [['observacion'], 'string', 'max' => 200],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['id_pedido' => 'id_pedido']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_tipo_factura'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFacturaVenta::className(), 'targetAttribute' => ['id_tipo_factura' => 'id_tipo_factura']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
 
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_factura' => 'Id:',
            'id_pedido' => 'No pedido:',
            'id_cliente' => 'Cliente:',
            'id_tipo_factura' => 'Tipo factura:',
            'numero_factura' => 'Numero factura:',
            'nit_cedula' => 'Nit/cedula:',
            'dv' => 'Dv',
            'cliente' => 'Cliente:',
            'numero_resolucion' => 'Numero resolucion:',
            'desde' => 'Inicio:',
            'hasta' => 'Final:',
            'consecutivo' => 'Consecutivo',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_vencimiento' => 'Fecha vencimiento:',
            'fecha_generada' => 'Fecha generada:',
            'fecha_enviada' => 'Fecha Enviada',
            'subtotal_factura' => 'Subtotal:',
            'descuento' => 'Descuento:',
            'impuesto' => 'Impuesto:',
            'total_factura' => 'Total pagar:',
            'porcentaje_iva' => '% Iva:',
            'porcentaje_rete_iva' => '% Rete iva.',
            'porcentaje_rete_fuente' => '% Rete fuente',
            'valor_retencion' => 'Retencion:',
            'valor_reteiva' => 'Reteiva:',
            'porcentaje_descuento' => '% Descto:',
            'saldo_factura' => 'Saldo factura',
            'forma_pago' => 'Forma pago:',
            'plazo_pago' => 'Plazo:',
            'autorizado' => 'Autorizado:',
            'user_name' => 'User Name:',
            'direccion' => 'Direccion:',
            'telefono_cliente' => 'Telefono:',
            'valor_bruto' => 'Valor bruto:',
            'observacion' => 'Observacion:',
            'fecha_editada' => 'fecha_editada',
            'user_name_editado' => 'user_name_editado',
            'id_agente' => 'Vendedor:',
            'estado_factura' => 'Estado factura:',
            'subtotal_interes_masiva' => 'Subtotal interes mas iva:',
            'iva_intereses_mora' => 'Iva x mora:',
            'valor_intereses_mora' => 'Valor intereses mora:',
            'dias_mora' => 'Dias en mora:',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedidos::className(), ['id_pedido' => 'id_pedido']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteFactura()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

     public function getAgenteFactura()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoFactura()
    {
        return $this->hasOne(TipoFacturaVenta::className(), ['id_tipo_factura' => 'id_tipo_factura']);
    }
    
    public function getFormaPago() {
        if($this->forma_pago == 1){
            $formapago = 'CONTADO';
        }else{
            $formapago = 'CREDITO';
        }
        return $formapago;
    }
    public function getAutorizadofactura() {
        if($this->autorizado == 0){
            $autorizadofactura = 'NO';
        }else{
            $autorizadofactura = 'SI';
        }
        return $autorizadofactura;
    }
    
    public function getEstadofactura() {
        if($this->estado_factura == 0){
            $estadofactura = 'ACTIVA';
        }else{
            if($this->estado_factura == 1){    
                $estadofactura = 'ABONADA';
            }else{
                if($this->estado_factura == 2){ 
                  $estadofactura = 'CANCELADA';
                }else{
                    if($this->estado_factura == 3){ 
                       $estadofactura = 'ANULADA';  
                    }else{
                        $estadofactura = 'NOTA CREDITO';  
                    }   
                }  
            }    
        }
        return $estadofactura;
    }
}
