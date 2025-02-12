<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "packing_pedido".
 *
 * @property int $id_packing
 * @property int $id_pedido
 * @property int $id_cliente
 * @property int $nit_cedula_cliente
 * @property string $cliente
 * @property string $fecha_creacion
 * @property string $fecha_packing
 * @property int $unidades_caja
 * @property int $numero_pedido
 * @property string $numero_guia
 * @property string $user_name
 *
 * @property Pedidos $pedido
 * @property Clientes $cliente0
 * @property PackingPedidoDetalle[] $packingPedidoDetalles
 */
class PackingPedido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packing_pedido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pedido', 'id_cliente', 'nit_cedula_cliente', 'total_unidades_packing', 'numero_pedido','numero_packing','total_cajas','estado_packing',
                'cerrado_proceso','id_transportadora','unidades_caja'], 'integer'],
            [['fecha_creacion', 'fecha_packing'], 'safe'],
            [['cliente'], 'string', 'max' => 50],
            [['numero_guia'], 'string', 'max' => 20],
            [['user_name'], 'string', 'max' => 15],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['id_pedido' => 'id_pedido']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_transportadora'], 'exist', 'skipOnError' => true, 'targetClass' => Transportadora::className(), 'targetAttribute' => ['id_transportadora' => 'id_transportadora']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_packing' => 'Id',
            'numero_packing' => 'Numero packing:',
            'id_pedido' => 'Id pedido',
            'id_cliente' => 'Id Cliente',
            'nit_cedula_cliente' => 'Documento',
            'cliente' => 'Cliente:',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_packing' => 'Fecha Packing',
            'total_unidades_packing' => 'Total unidades:',
            'numero_pedido' => 'Numero pedido:',
            'numero_guia' => 'Numero guia:',
            'user_name' => 'User Name:',
            'total_cajas' => 'Total cajas:',
            'estado_packing' => 'Activo:',
            'cerrado_proceso' => 'Cerrado:',
            'id_transportadora' => 'Transportadora:',
            'unidades_caja' => 'unidades_caja',
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
    public function getTransportadora()
    {
        return $this->hasOne(Transportadora::className(), ['id_transportadora' => 'id_transportadora']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientePacking()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackingPedidoDetalles()
    {
        return $this->hasMany(PackingPedidoDetalle::className(), ['id_packing' => 'id_packing']);
    }
    
    //procesos apartes
    
    public function getCerradoProceso() {
        if($this->cerrado_proceso == 0){
            $procesocerrado = 'NO';
        }else {
            $procesocerrado = 'SI';
        }
        return $procesocerrado;
    }
    
    public function getAutorizadoProceso() {
        if($this->autorizado == 0){
            $autorizadoproceso = 'NO';
        }else {
            $autorizadoproceso = 'SI';
        }
        return $autorizadoproceso;
    }
    
     public function getEstadoProceso() {
        if($this->estado_packing == 0){
            $estadoproceso = 'NO';
        }else {
            $estadoproceso = 'SI';
        }
        return $estadoproceso;
    }
}
