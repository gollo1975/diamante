<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remisiones".
 *
 * @property int $id_remision
 * @property int $id_cliente
 * @property int $numero_remision
 * @property string $fecha_inicio
 * @property string $fecha_hora_registro
 * @property int $valor_bruto
 * @property int $descuento
 * @property int $subtotal
 * @property int $total_remision
 * @property int $autorizado
 * @property string $user_name
 * @property string $observacion
 * @property string $fecha_editada
 * @property string $user_name_editado
 * @property int $estado_remision
 * @property int $id_punto
 * @property int $exportar_inventario
 *
 * @property RemisionDetalles[] $remisionDetalles
 * @property Clientes $cliente
 */
class Remisiones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remisiones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'numero_remision', 'valor_bruto', 'descuento', 'subtotal', 'total_remision', 'autorizado', 'estado_remision', 'id_punto',
                'exportar_inventario','expedir_factura'], 'integer'],
            [['fecha_inicio', 'fecha_hora_registro', 'fecha_editada'], 'safe'],
            [['user_name', 'user_name_editado'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 200],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_remision' => 'Id:',
            'id_cliente' => 'Cliente:',
            'numero_remision' => 'Numero remision:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_hora_registro' => 'Fecha Hora Registro:',
            'valor_bruto' => 'Valor bruto:',
            'descuento' => 'Descuento:',
            'subtotal' => 'Subtotal:',
            'total_remision' => 'Total remision:',
            'autorizado' => 'Autorizado',
            'user_name' => 'User name:',
            'observacion' => 'Observacion:',
            'fecha_editada' => 'Fecha editada:',
            'user_name_editado' => 'User name editado:',
            'estado_remision' => 'Estado:',
            'id_punto' => 'Punto de venta',
            'exportar_inventario' => 'Exportar inventario:',
            'expedir_factura' => 'expedir_factura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemisionDetalles()
    {
        return $this->hasMany(RemisionDetalles::className(), ['id_remision' => 'id_remision']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPuntoVenta()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
    }
    
    public function getAutorizadoRemision(){
        if($this->autorizado == 0){
            $autorizadoremision = 'NO';
        }else{
            $autorizadoremision = 'SI';
        }
        return $autorizadoremision;
    }
    
    public function getExportarInventario(){
        if($this->exportar_inventario == 0){
            $exportarinventario = 'NO';
        }else{
            $exportarinventario = 'SI';
        }
        return $exportarinventario;
    }
    
    public function getExpedirFactura(){
        if($this->expedir_factura == 0){
            $expedirfactura = 'NO';
        }else{
            $expedirfactura = 'SI';
        }
        return $expedirfactura;
    }
    public function getEstadoRemision(){
        if($this->estado_remision == 0){
            $estadoremision = 'ACTIVA';
        }else{
            if($this->estado_remision == 1){
                 $estadoremision = 'CANCELADA';
            }else{
                $estadoremision = 'ANULADA';
            }
        }
        return $estadoremision;
    }
}
