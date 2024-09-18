<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedidos".
 *
 * @property int $id_pedido
 * @property int $numero_pedido
 * @property int $id_cliente
 * @property string $documento
 * @property int $dv
 * @property string $cliente
 * @property int $cantidad
 * @property int $subtotal
 * @property int $impuesto
 * @property int $gran_total
 * @property int $autorizado
 * @property int $cerrar_pedido
 * @property string $usuario
 * @property string $fecha_proceso
 * @property int $facturado
 *
 * @property Clientes $cliente0
 */
class Pedidos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedidos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_pedido', 'id_cliente','id_agente', 'dv', 'cantidad', 'subtotal', 'impuesto', 'gran_total', 'autorizado', 'cerrar_pedido', 'facturado',
                'valor_presupuesto','presupuesto','pedido_anulado','valor_eliminado_presupuesto','valor_eliminado_pedido','pedido_validado',
                'pedido_virtual','liberado_inventario'], 'integer'],
            [['fecha_proceso'], 'required'],
            [['fecha_proceso','fecha_entrega','fecha_cierre_alistamiento'], 'safe'],
            [['documento', 'usuario'], 'string', 'max' => 15],
            [['cliente'], 'string', 'max' => 50],
            [['observacion'], 'string', 'max' => 100], 
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pedido' => 'Id:',
            'numero_pedido' => 'Numero pedido:',
            'documento' => 'Documento:',
            'dv' => 'Dv',
            'cliente' => 'Nombre del cliente:',
            'cantidad' => 'Cantidad:',
            'subtotal' => 'Subtotal:',
            'impuesto' => 'Impuesto:',
            'gran_total' => 'Gran total:',
            'autorizado' => 'Autorizado:',
            'cerrar_pedido' => 'Pedido cerrado:',
            'usuario' => 'Usuario',
            'fecha_proceso' => 'F. pedido',
            'facturado' => 'Facturado',
            'id_agente'=> 'Agente comercial:',
            'observacion' => 'Nota:',
            'presupuesto' => 'Presupuesto:',
            'valor_presupuesto' => 'Valor presupuesto:',
            'pedido_anulado' => 'Pedido anulado:',
            'valor_eliminado_pedido' => 'valor_eliminado_pedido',
            'valor_eliminado_presupuesto' => 'valor_eliminado_presupuesto',
            'pedido_validado' => 'Pedido validado:',
            'pedido_virtual' => 'Pedido virtual:',
            'fecha_entrega' => 'F. entrega:',
            'fecha_cierre_alistamiento' => 'Fecha validado:',
            'liberado_inventario' => 'Liberado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientePedido()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }
    public function getAgentePedido()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
    public function getPedidoAbierto() {
        if($this->cerrar_pedido == 0 ){
            $pedidoabierto = 'NO';
        }else{
            $pedidoabierto = 'SI';
        }
        return $pedidoabierto;
    }
    public function getPedidoFacturado() {
        if($this->facturado == 0 ){
            $pedidofacturado = 'NO';
        }else{
            $pedidofacturado = 'SI';
        }
        return $pedidofacturado;
    }
    public function getAutorizadoPedido() {
        if($this->autorizado == 0 ){
            $autorizadopedido = 'NO';
        }else{
            $autorizadopedido = 'SI';
        }
        return $autorizadopedido;
    }
     public function getPresupuestoPedido() {
        if($this->presupuesto == 0 ){
            $presupuestopedido = 'NO';
        }else{
            $presupuestopedido = 'SI';
        }
        return $presupuestopedido;
    }
     public function getPedidoAnulado() {
        if($this->pedido_anulado == 0 ){
            $pedidoanulado = 'NO';
        }else{
            $pedidoanulado = 'SI';
        }
        return $pedidoanulado;
    }
    public function getPedidoVirtual() {
        if($this->pedido_virtual == 0 ){
            $pedidovirtual = 'NO';
        }else{
            $pedidovirtual = 'SI';
        }
        return $pedidovirtual;
    }
     public function getPedidoValidado() {
        if($this->pedido_validado == 0 ){
            $pedidovalidado = 'NO';
        }else{
            $pedidovalidado = 'SI';
        }
        return $pedidovalidado;
    }
    
     public function getPedidoLiberado() {
        if($this->liberado_inventario == 0 ){
            $pedidoliberado = 'NO';
        }else{
            $pedidoliberado = 'SI';
        }
        return $pedidoliberado;
    }
    
}
