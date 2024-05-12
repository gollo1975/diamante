<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada_productos_inventario".
 *
 * @property int $id_entrada
 * @property int $id_proveedor
 * @property int $id_orden_compra
 * @property string $fecha_proceso
 * @property string $fecha_registro
 * @property string $numero_soporte
 * @property int $total_unidades
 * @property int $subtotal
 * @property int $impuesto
 * @property int $total_salida
 * @property int $autorizado
 * @property int $enviar_materia_prima
 * @property string $user_name_crear
 * @property string $user_name_edit
 * @property string $observacion
 * @property int $tipo_entrada
 *
 * @property EntradaProductoInventarioDetalle[] $entradaProductoInventarioDetalles
 * @property Proveedor $proveedor
 * @property OrdenCompra $ordenCompra
 */
class EntradaProductosInventario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada_productos_inventario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_proveedor'], 'required'],
            [['id_proveedor', 'id_orden_compra', 'total_unidades', 'subtotal', 'impuesto', 'total_salida', 'autorizado', 'enviar_materia_prima', 'tipo_entrada','id_tipo_orden'], 'integer'],
            [['fecha_proceso', 'fecha_registro'], 'safe'],
            [['observacion'], 'string'],
            [['numero_soporte'], 'string', 'max' => 10],
            [['user_name_crear', 'user_name_edit'], 'string', 'max' => 15],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::className(), 'targetAttribute' => ['id_proveedor' => 'id_proveedor']],
            [['id_orden_compra'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenCompra::className(), 'targetAttribute' => ['id_orden_compra' => 'id_orden_compra']],
            [['id_tipo_orden'], 'exist', 'skipOnError' => true, 'targetClass' => TipoOrdenCompra::className(), 'targetAttribute' => ['id_tipo_orden' => 'id_tipo_orden']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entrada' => 'Id',
            'id_proveedor' => 'Proveedor:',
            'id_orden_compra' => 'Orden de compra:',
            'fecha_proceso' => 'Fecha proceso',
            'fecha_registro' => 'Fecha registro',
            'numero_soporte' => 'Numero soporte',
            'total_unidades' => 'Total unidades',
            'subtotal' => 'Subtotal',
            'impuesto' => 'Impuesto',
            'total_salida' => 'Total salida',
            'autorizado' => 'Autorizado',
            'enviar_materia_prima' => 'Inventario enviado',
            'user_name_crear' => 'User Name Crear',
            'user_name_edit' => 'User Name Edit',
            'observacion' => 'Observacion',
            'tipo_entrada' => 'Tipo Entrada',
            'id_tipo_orden' => 'Tipo de orden:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntradaProductoInventarioDetalles()
    {
        return $this->hasMany(EntradaProductoInventarioDetalle::className(), ['id_entrada' => 'id_entrada']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::className(), ['id_proveedor' => 'id_proveedor']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoOrden()
    {
        return $this->hasOne(TipoOrdenCompra::className(), ['id_tipo_orden' => 'id_tipo_orden']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenCompra()
    {
        return $this->hasOne(OrdenCompra::className(), ['id_orden_compra' => 'id_orden_compra']);
    }
    public function getTipoEntrada() {
        if($this->tipo_entrada == 1){
            $tipoentrada = 'ORDEN DE COMPRA';
        }else{
            $tipoentrada = 'MANUAL';
        }
        return $tipoentrada;
    }
    
    public function getAutorizadoCompra() {
        if($this->autorizado == 0){
            $autorizadocompra = 'NO';
        }else{
            $autorizadocompra = 'SI';
        }
        return $autorizadocompra;
    }
    public function getEnviarMateria() {
        if($this->enviar_materia_prima == 0){
            $enviarmateria = 'NO';
        }else{
            $enviarmateria = 'SI';
        }
        return $enviarmateria;
    }
    
}
