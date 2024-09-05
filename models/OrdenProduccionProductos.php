<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_produccion_productos".
 *
 * @property int $id_detalle
 * @property int $id_orden_produccion
 * @property int $codigo_producto
 * @property string $descripcion
 * @property int $cantidad
 * @property int $user_name
 * @property int $cerrar_linea
 *
 * @property OrdenProduccion $ordenProduccion
 */
class OrdenProduccionProductos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_produccion_productos';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'codigo_producto', 'cantidad', 'cerrar_linea','id_medida_producto','aplica_iva','id_inventario',
                'importado','costo_unitario','cantidad_real','id_presentacion','orden_ensamble_creado'], 'integer'],
            [['descripcion'], 'string', 'max' => 40],
            [['user_name','numero_lote'], 'string', 'max' => 15],
            ['porcentaje_iva', 'number'],
            ['fecha_vencimiento', 'safe'],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_medida_producto'], 'exist', 'skipOnError' => true, 'targetClass' => MedidaProductoTerminado::className(), 'targetAttribute' => ['id_medida_producto' => 'id_medida_producto']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => PresentacionProducto::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_orden_produccion' => 'Id Orden Produccion',
            'codigo_producto' => 'Codigo Producto',
            'descripcion' => 'Descripcion',
            'cantidad' => 'Proyectada',
            'cantidad_real' => 'Cantidad real:',
            'user_name' => 'User Name',
            'cerrar_linea' => 'Cerrar Linea',
            'numero_lote' => 'Numero lote',
            'id_medida_producto' => 'Medida',
            'aplica_iva' => 'aplica_iva',
            'porcentaje_iva' => 'porcentaje_iva',
            'id_inventario' => 'id_inventario',
            'importado' => 'importado',
            'fecha_vencimiento' => 'fecha_vencimiento',
            'costo_unitario' => 'costo_unitario',
            'id_presentacion' => 'id_presentacion' ,
            'orden_ensamble_creado' => 'orden_ensamble_creado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresentacionProducto()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }
    
    public function getMedidaProducto()
    {
        return $this->hasOne(MedidaProductoTerminado::className(), ['id_medida_producto' => 'id_medida_producto']);
    }
    
    public function getCerrarLinea() {
        if($this->cerrar_linea == 0){
           $cerrarlinea = 'NO';
        }else{
            $cerrarlinea = 'SI';
        }
        return $cerrarlinea;
    }
     public function getAplicaIva() {
        if($this->aplica_iva == 0){
           $aplicaiva = 'SI';
        }else{
            $aplicaiva = 'NO';
        }
        return $aplicaiva;
    }
     public function getDocumentoExportado() {
        if($this->importado == 0){
           $documentoexportado = 'NO';
        }else{
            $documentoexportado = 'SI';
        }
        return $documentoexportado;
    }
}
