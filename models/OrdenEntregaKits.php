<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_entrega_kits".
 *
 * @property int $id_orden_entrega
 * @property int $id_entrega_kits
 * @property int $id_presentacion
 * @property int $id_inventario
 * @property int $total_kits
 * @property int $total_productos_procesados
 * @property string $fecha_orden
 * @property string $fecha_hora_registro
 * @property int $autorizado
 * @property int $proceso_cerrado
 * @property string $user_name
 *
 * @property EntregaSolicitudKits $entregaKits
 * @property PresentacionProducto $presentacion
 * @property InventarioProductos $inventario
 * @property OrdenEntregaKitsDetalles[] $ordenEntregaKitsDetalles
 */
class OrdenEntregaKits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_entrega_kits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrega_kits', 'id_presentacion', 'id_inventario', 'total_kits', 'total_productos_procesados', 'autorizado', 'proceso_cerrado','numero_orden','inventario_enviado'], 'integer'],
            [['fecha_orden', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
            [['id_entrega_kits'], 'exist', 'skipOnError' => true, 'targetClass' => EntregaSolicitudKits::className(), 'targetAttribute' => ['id_entrega_kits' => 'id_entrega_kits']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => PresentacionProducto::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_orden_entrega' => 'Id',
            'id_entrega_kits' => 'Numero solicitud kits:',
            'id_presentacion' => 'Presentacion del producto:',
            'id_inventario' => 'INventario:',
            'total_kits' => 'Numero total kits:',
            'total_productos_procesados' => 'Total productos entregados:',
            'fecha_orden' => 'Fecha proceso:',
            'fecha_hora_registro' => 'Fecha hora registro:',
            'autorizado' => 'Autorizado:',
            'proceso_cerrado' => 'Proceso cerrado:',
            'user_name' => 'User name:',
            'numero_orden' => 'numero_orden:',
            'inventario_enviado' =>'inventario_enviado',
            'observacion' => 'Observacion:',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregaKits()
    {
        return $this->hasOne(EntregaSolicitudKits::className(), ['id_entrega_kits' => 'id_entrega_kits']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresentacion()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenEntregaKitsDetalles()
    {
        return $this->hasMany(OrdenEntregaKitsDetalles::className(), ['id_orden_entrega' => 'id_orden_entrega']);
    }
    
    public function getAutorizadoProceso() {
        if($this->autorizado == 0){
            $autorizadoproceso = 'NO';
        }else{
            $autorizadoproceso = 'SI';
        }
        return $autorizadoproceso;
    }
    
     public function getProcesoCerrado() {
        if($this->proceso_cerrado== 0){
            $procesocerrado = 'NO';
        }else{
            $procesocerrado = 'SI';
        }
        return $procesocerrado;
    }
}
