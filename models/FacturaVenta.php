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
          
            [['id_pedido', 'id_cliente', 'id_tipo_factura', 'numero_factura', 'dv','id_forma_pago', 'plazo_pago', 'autorizado','id_agente','estado_factura','dias_mora','valor_intereses_mora',
                'iva_intereses_mora', 'subtotal_interes_masiva','id_tipo_venta','tipo_inventario','id_punto','descuento_comercial','id_resolucion',
                'valor_pago_descuento_dos','valor_pago_descuento_uno','id_medio_pago'], 'integer'],
            
            [['desde', 'hasta', 'fecha_inicio', 'fecha_vencimiento', 'fecha_generada', 'fecha_enviada_api','fecha_editada','fecha_enviada_dian','fecha_primer_descuento','fecha_segundo_descuento'], 'safe'],
            //campos float nacional
            [['porcentaje_iva', 'porcentaje_rete_iva', 'porcentaje_rete_fuente', 'porcentaje_descuento','porcentaje_mora',
             'subtotal_factura','descuento','impuesto','total_factura','valor_retencion','valor_bruto','valor_reteiva','saldo_factura'], 'number'],
            //campos float internacional
            [['valor_bruto_internacional','descuento_internacional','descuento_comercial_internacional','subtotal_factura_internacional','impuesto_internacional',
              'total_factura_internacional','valor_retencion_internacional','valor_reteiva_internacional','saldo_factura_internacional','valor_moneda'],'number'],
            //campos string
            [['nit_cedula', 'user_name','telefono_cliente','user_name_editado'], 'string', 'max' => 15],
            [['cliente','direccion','nota1', 'nota2'], 'string', 'max' => 50],
            [['nota1', 'nota2'], 'string', 'max' => 150],
            [['numero_resolucion'], 'string', 'max' => 30],
            [['consecutivo'], 'string', 'max' => 3],
            [['observacion'], 'string', 'max' => 200],
            [['cufe'], 'string' ],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['id_pedido' => 'id_pedido']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_tipo_factura'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFacturaVenta::className(), 'targetAttribute' => ['id_tipo_factura' => 'id_tipo_factura']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
            [['id_tipo_venta'], 'exist', 'skipOnError' => true, 'targetClass' => TipoVenta::className(), 'targetAttribute' => ['id_tipo_venta' => 'id_tipo_venta']],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
            [['id_resolucion'], 'exist', 'skipOnError' => true, 'targetClass' => ResolucionDian::className(), 'targetAttribute' => ['id_resolucion' => 'id_resolucion']],
            [['id_forma_pago'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['id_forma_pago' => 'id_forma_pago']],
            [['id_medio_pago'], 'exist', 'skipOnError' => true, 'targetClass' => MedioPago::className(), 'targetAttribute' => ['id_medio_pago' => 'id_medio_pago']],
 
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
            'fecha_enviada_api' => 'Fecha Enviada api',
            'fecha_enviada_dian' => 'fecha_enviada_dian',
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
            'id_forma_pago' => 'Forma pago:',
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
            'id_tipo_venta' => 'Tipo de venta:',
            'tipo_inventario' => 'Tipo inventario:',
            'id_punto' => 'Punto de venta:',
            'descuento_comercial' => 'descuento_comercial',
            'id_resolucion' => 'Resolucion',
            'cufe' => 'cufe',
            'valor_pago_descuento_uno' => 'valor_pago_descuento_uno',
            'valor_pago_descuento_dos' => 'valor_pago_descuento_dos',
            'valor_moneda' => 'Tasa de cambio:',
            'id_medio_pago' => 'Medio de pago:'
            
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
    public function getResolucionDian()
    {
        return $this->hasOne(ResolucionDian::className(), ['id_resolucion' => 'id_resolucion']);
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
     public function getTipoVenta()
    {
        return $this->hasOne(TipoVenta::className(), ['id_tipo_venta' => 'id_tipo_venta']);
    }
    
    public function getPuntoVenta()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedioPago()
    {
        return $this->hasOne(MedioPago::className(), ['id_medio_pago' => 'id_medio_pago']);
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
    
     public function getTipoInventario() {
        if($this->tipo_inventario == 0){
            $tipoinventario = 'PRODUCCION';
        }else{
            $tipoinventario = 'PUNTO DE VENTA';
        }
        return $tipoinventario;
    }
    
}
