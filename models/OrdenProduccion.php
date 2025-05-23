<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_produccion".
 *
 * @property int $id_orden_produccion
 * @property int $numero_orden
 * @property int $id_almacen
 * @property int $id_grupo
 * @property int $numero_lote
 * @property string $fecha_proceso
 * @property string $fecha_entrega
 * @property string $fecha_registro
 * @property int $subtotal
 * @property int $iva
 * @property int $total_orden
 * @property string $user_name
 * @property int $autorizado
 * @property int $cerrar_orden
 *
 * @property Almacen $almacen
 * @property GrupoProducto $grupo
 */
class OrdenProduccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_produccion';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->responsable = strtoupper($this->responsable); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_orden', 'id_almacen', 'id_grupo', 'numero_lote', 'subtotal', 'iva', 'total_orden', 'autorizado', 'cerrar_orden',
                'tipo_orden', 'unidades', 'costo_unitario','producto_aprobado','producto_almacenado','exportar_inventario','exportar_materia_prima',
                'tamano_lote','id_proceso_produccion','seguir_proceso_ensamble','proceso_auditado','orden_cerrada_ensamble','id_producto','unidades_reales'], 'integer'],
            [['id_almacen', 'id_grupo', 'fecha_proceso', 'fecha_entrega', 'responsable','id_proceso_produccion','id_producto'], 'required'],
            [['fecha_proceso', 'fecha_entrega', 'fecha_registro','fecha_cambio'], 'safe'],
            [['user_name', 'numero_lote'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
            ['responsable', 'string', 'max' => 40],
            [['id_almacen'], 'exist', 'skipOnError' => true, 'targetClass' => Almacen::className(), 'targetAttribute' => ['id_almacen' => 'id_almacen']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_proceso_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => TipoProcesoProduccion::className(), 'targetAttribute' => ['id_proceso_produccion' => 'id_proceso_produccion']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Productos::className(), 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_orden_produccion' => 'Id:',
            'numero_orden' => 'Numero orden:',
            'id_almacen' => 'Almacen/Bodega:',
            'id_grupo' => 'Grupo producto:',
            'id_producto' => 'Producto:',
            'numero_lote' => 'Numero lote:',
            'fecha_proceso' => 'Fecha proceso:',
            'fecha_entrega' => 'Fecha entrega:',
            'fecha_registro' => 'Fecha registro:',
            'subtotal' => 'Subtotal:',
            'iva' => 'Iva:',
            'total_orden' => 'Total costo',
            'user_name' => 'User Name',
            'autorizado' => 'Autorizado:',
            'cerrar_orden' => 'Orden cerrada:',
            'tipo_orden' => 'Tipo orden:',
            'observacion' => 'Observacion:',
            'unidades' => 'Unidades:',
            'costo_unitario' => 'Costo unitario:',
            'responsable' => 'Responsable:',
            'producto_aprobado' => 'Producto aprobado:',
            'producto_almacenado' => 'Producto almacenado:',
            'exportar_materia_prima' => 'exportar_materia_prima',
            'exportar_inventario' => 'exportar_inventario',
            'tamano_lote' => 'Tamaño del lote:',
            'id_proceso_produccion' => 'Proceso de produccion:',
            'seguir_proceso_ensamble' => 'seguir_proceso_ensamble',
            'fecha_cambio' => 'fecha_cambio',
            'proceso_auditado' => 'proceso_auditado',
            'orden_cerrada_ensamble' => 'orden_cerrada_ensamble',
            'unidades_reales' => 'unidades_reales',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacen()
    {
        return $this->hasOne(Almacen::className(), ['id_almacen' => 'id_almacen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoProceso()
    {
        return $this->hasOne(TipoProcesoProduccion::className(), ['id_proceso_produccion' => 'id_proceso_produccion']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Productos::className(), ['id_producto' => 'id_producto']);
    }
    
     public function getOrdenEnsambleConsulta()
    {
        return " Numero orden: {$this->numero_orden} - Id: {$this->id_orden_produccion}";
    }
    
    public function getAutorizadoOrden() {
        if($this->autorizado == 0 ){
            $autorizadoorden = 'NO';
        }else{
            $autorizadoorden = 'SI';
        }
        return $autorizadoorden;
    }
    
    public function getCerrarOrden() {
        if($this->cerrar_orden == 0 ){
            $cerrarorden = 'NO';
        }else{
            $cerrarorden = 'SI';
        }
        return $cerrarorden;
    }
    
    public function getTipoOrden() {
        if($this->tipo_orden == 0 ){
            $tipoorden = 'PORTAFOLIO ACTUAL';
        }else{
            $tipoorden = 'NUEVO PRODUCTO';
        }
        return $tipoorden;
    }
   
    public function getProductoAprobado() {
        if($this->producto_aprobado == 0 ){
            $productoaprobado = 'NO';
        }else{
            $productoaprobado = 'SI';
        }
        return $productoaprobado;
    }
    public function getProductoAlmacenado() {
        if($this->producto_almacenado == 0 ){
            $productoalmacenado = 'NO';
        }else{
            $productoalmacenado = 'SI';
        }
        return $productoalmacenado;
    }
    
    public function getSeguirProcesoEnsamble() {
        if($this->seguir_proceso_ensamble == 0 ){
            $seguirprocesoensamble = 'NO';
        }else{
            $seguirprocesoensamble = 'SI';
        }
        return $seguirprocesoensamble;
    }
    public function getProcesoAuditado() {
        if($this->proceso_auditado == 0 ){
            $procesoauditado = 'NO';
        }else{
            $procesoauditado = 'SI';
        }
        return $procesoauditado;
    }
    
}
