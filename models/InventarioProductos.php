<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario_productos".
 *
 * @property int $id_inventario
 * @property int $codigo_producto
 * @property string $nombre_producto
 * @property string $descripcion_producto
 * @property int $costo_unitario
 * @property int $unidades_entradas
 * @property int $stock_unidades
 * @property int $id_medida_producto
 * @property int $id_detalle
 * @property int $aplica_iva
 * @property int $inventario_inicial
 * @property int $aplica_inventario
 * @property double $porcentaje_iva
 * @property int $subtotal
 * @property int $valor_iva
 * @property int $total_inventario
 * @property int $precio_venta_uno
 * @property int $precio_venta_dos
 * @property int $precio_venta_tres
 * @property string $fecha_vencimiento
 * @property string $fecha_creacion
 * @property string $fecha_proceso
 * @property string $user_name
 * @property int $codigo_ean
 *
 * @property MedidaProductoTerminado $medidaProducto
 * @property OrdenProduccionProductos $detalle
 */
class InventarioProductos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario_productos';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_producto = strtoupper($this->nombre_producto); 
        $this->descripcion_producto = strtoupper($this->descripcion_producto); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_producto','fecha_proceso','id_grupo','id_presentacion'], 'required'],
            [['codigo_producto', 'costo_unitario', 'unidades_entradas', 'stock_unidades', 'id_grupo', 'id_detalle', 'aplica_iva', 'inventario_inicial', 'aplica_inventario',
                'subtotal', 'valor_iva', 'total_inventario', 'precio_deptal', 'precio_mayorista', 'codigo_ean',
                'venta_publico','id_presentacion','aplica_presupuesto','aplica_regla_comercial','activar_producto_venta','aplica_descuento','aplica_descuento_distribuidor'], 'integer'],
            [['porcentaje_iva'], 'number'],
            [['fecha_vencimiento', 'fecha_creacion', 'fecha_proceso'], 'safe'],
            [['nombre_producto'], 'string', 'max' => 40],
            [['descripcion_producto'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_detalle'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccionProductos::className(), 'targetAttribute' => ['id_detalle' => 'id_detalle']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => PresentacionProducto::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::className(), 'targetAttribute' => ['id_proveedor' => 'id_proveedor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_inventario' => 'Id',
            'codigo_producto' => 'Codigo:',
            'nombre_producto' => 'Producto:',
            'descripcion_producto' => 'Descripcion:',
            'costo_unitario' => 'Costo:',
            'unidades_entradas' => 'Cantidad:',
            'stock_unidades' => 'Stock:',
            'id_grupo' => 'Grupo:',
            'id_detalle' => 'Detalle',
            'aplica_iva' => 'Aplica iva:',
            'inventario_inicial' => 'Inv. inicial:',
            'aplica_inventario' => 'Aplica inventario:',
            'porcentaje_iva' => '% Iva:',
            'subtotal' => 'Subtotal:',
            'valor_iva' => 'Impuesto:',
            'total_inventario' => 'Total:',
            'precio_deptal' => 'Precio deptal:',
            'precio_mayorista' => 'Precio mayorista:',
            'fecha_vencimiento' => 'Fecha vencimiento:',
            'fecha_creacion' => 'Fecha creacion:',
            'fecha_proceso' => 'Fecha proceso:',
            'user_name' => 'User name:',
            'codigo_ean' => 'Codigo Ean:',
            'venta_publico' => 'Venta publico:',
            'id_presentacion' => 'Presentacion:',
            'aplica_presupuesto' => 'Presupuesto',
            'aplica_regla_comercial' => 'Aplica regla comercial:',
            'activar_producto_venta' => 'Activar producto:',
            'id_proveedor' => 'Proveedor:',
            'aplica_descuento' => 'Aplica descuento:',
            'aplica_descuento_distribuidor' => 'aplica_descuento_distribuidor',
           
         ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }

    public function getPresentacion()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }
    
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::className(), ['id_proveedor' => 'id_proveedor']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasOne(OrdenProduccionProductos::className(), ['id_detalle' => 'id_detalle']);
    }
    //proceso que incrita varios valores
     public function getInventario()
    {
        return "{$this->codigo_producto} - {$this->nombre_producto}";
    }
    
        
    public function getAplicaIva() {
        if($this->aplica_iva == 0){
           $aplicaiva = 'SI';
        }else{
            $aplicaiva = 'NO';
        }
        return $aplicaiva;
    }
    
    public function getInventarioInicial() {
        if($this->inventario_inicial == 0){
           $inventario_inicial = 'SI';
        }else{
            $inventario_inicial = 'NO';
        }
        return $inventario_inicial;
    }
    public function getAplicaInventario() {
        if($this->aplica_inventario == 0){
           $aplicainventario = 'SI';
        }else{
            $aplicainventario = 'NO';
        }
        return $aplicainventario;
    }

    public function getVenta_Publico() {
        if($this->venta_publico == 0){
           $ventapublico = 'SI';
        }else{
            $ventapublico = 'NO';
        }
        return $ventapublico;
    }
    public function getAplicaPresupuesto() {
        if($this->aplica_presupuesto == 0){
           $aplicapresupuesto = 'NO';
        }else{
            $aplicapresupuesto = 'SI';
        }
        return $aplicapresupuesto;
    }
    
     public function getAplicaRegla() {
        if($this->aplica_regla_comercial == 0){
           $aplicareglacomercial = 'NO';
        }else{
            $aplicareglacomercial = 'SI';
        }
        return $aplicareglacomercial;
    }

     public function getActivarProducto() {
        if($this->activar_producto_venta == 0){
           $activarproducto = 'NO';
        }else{
            $activarproducto = 'SI';
        }
        return $activarproducto;
    }
    
    public function getAplicaDescuento() {
        if($this->aplica_descuento == 0){
           $aplicadescuento = 'NO';
        }else{
            $aplicadescuento = 'SI';
        }
        return $aplicadescuento;
    }
    
    public function getDescuentoDistribuidor() {
        if($this->aplica_descuento_distribuidor == 0){
           $aplicadescuentodistribuidor = 'NO';
        }else{
            $aplicadescuentodistribuidor = 'SI';
        }
        return $aplicadescuentodistribuidor;
    }
}
