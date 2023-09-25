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
            [['id_pedido', 'id_cliente', 'id_tipo_factura', 'numero_factura', 'dv', 'subtotal_factura', 'descuento', 'impuesto', 'total_factura', 'valor_retencion', 'valor_reteiva', 'saldo_factura', 'forma_pago', 'plazo_pago', 'autorizado'], 'integer'],
            [['desde', 'hasta', 'fecha_inicio', 'fecha_vencimiento', 'fecha_generada', 'fecha_enviada'], 'safe'],
            [['porcentaje_iva', 'porcentaje_rete_iva', 'porcentaje_rete_fuente', 'porcentaje_descuento'], 'number'],
            [['nit_cedula', 'user_name','telefono_cliente'], 'string', 'max' => 15],
            [['cliente','direccion'], 'string', 'max' => 50],
            [['numero_resolucion'], 'string', 'max' => 30],
            [['consecutivo'], 'string', 'max' => 3],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['id_pedido' => 'id_pedido']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_tipo_factura'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFacturaVenta::className(), 'targetAttribute' => ['id_tipo_factura' => 'id_tipo_factura']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_factura' => 'Id Factura',
            'id_pedido' => 'Id Pedido',
            'id_cliente' => 'Id Cliente',
            'id_tipo_factura' => 'Id Tipo Factura',
            'numero_factura' => 'Numero Factura',
            'nit_cedula' => 'Nit Cedula',
            'dv' => 'Dv',
            'cliente' => 'Cliente',
            'numero_resolucion' => 'Numero Resolucion',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'consecutivo' => 'Consecutivo',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_vencimiento' => 'Fecha Vencimiento',
            'fecha_generada' => 'Fecha Generada',
            'fecha_enviada' => 'Fecha Enviada',
            'subtotal_factura' => 'Subtotal Factura',
            'descuento' => 'Descuento',
            'impuesto' => 'Impuesto',
            'total_factura' => 'Total Factura',
            'porcentaje_iva' => 'Porcentaje Iva',
            'porcentaje_rete_iva' => 'Porcentaje Rete Iva',
            'porcentaje_rete_fuente' => 'Porcentaje Rete Fuente',
            'valor_retencion' => 'Valor Retencion',
            'valor_reteiva' => 'Valor Reteiva',
            'porcentaje_descuento' => 'Porcentaje Descuento',
            'saldo_factura' => 'Saldo Factura',
            'forma_pago' => 'Forma Pago',
            'plazo_pago' => 'Plazo Pago',
            'autorizado' => 'Autorizado',
            'user_name' => 'User Name',
            'direccion' => 'direccion',
            'telefono_cliente' => 'telefono_cliente',
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
    public function getCliente0()
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
}
