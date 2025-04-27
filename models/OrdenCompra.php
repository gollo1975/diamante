<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_compra".
 *
 * @property int $id_orden_compra
 * @property int $id_tipo_orden
 * @property int $id_proveedor
 * @property string $fecha_creacion
 * @property string $fecha_proceso
 * @property string $numero_solicitud
 * @property int $subtotal
 * @property int $impuesto
 * @property int $total_orden
 * @property string $user_name
 * @property int $autorizado
 * @property int $numero_orden
 * @property string $observacion
 *
 * @property TipoOrdenCompra $tipoOrden
 * @property Proveedor $proveedor
 * @property OrdenCompraDetalle[] $ordenCompraDetalles
 */
class OrdenCompra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_compra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_orden', 'id_proveedor', 'fecha_creacion','fecha_entrega'], 'required'],
            [['id_tipo_orden', 'id_proveedor', 'subtotal', 'impuesto', 'total_orden', 'autorizado', 'numero_orden','id_solicitud_compra'], 'integer'],
            [['fecha_creacion', 'fecha_proceso'], 'safe'],
            [['observacion','descripcion','abreviatura'], 'string'],
            [['numero_solicitud', 'user_name'], 'string', 'max' => 15],
            [['id_tipo_orden'], 'exist', 'skipOnError' => true, 'targetClass' => TipoOrdenCompra::className(), 'targetAttribute' => ['id_tipo_orden' => 'id_tipo_orden']],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::className(), 'targetAttribute' => ['id_proveedor' => 'id_proveedor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_orden_compra' => 'Id',
            'id_tipo_orden' => 'Tipo orden:',
            'id_proveedor' => 'Proveedor:',
            'fecha_creacion' => 'Fecha creacion:',
            'fecha_proceso' => 'Fecha proceso:',
            'numero_solicitud' => 'Numero solicitud:',
            'subtotal' => 'Subtotal:',
            'impuesto' => 'Impuesto:',
            'total_orden' => 'Total orden:',
            'user_name' => 'User name:',
            'autorizado' => 'Autorizado:',
            'numero_orden' => 'Numero orden:',
            'observacion' => 'Observacion:',
            'fecha_entrega' => 'Fecha entrega:',
            'abreviatura' => 'Abreviatura:',
            'auditada' => 'Auditada:',
            'id_solicitud_compra' => 'id_solicitud_compra'
        ];
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
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::className(), ['id_proveedor' => 'id_proveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenCompraDetalles()
    {
        return $this->hasMany(OrdenCompraDetalle::className(), ['id_orden_compra' => 'id_orden_compra']);
    }
    
     //proceso que agrupa varios campos
    public function getOrdenCompraCompleto()
    {
        return " Numero orden: {$this->id_orden_compra} - Tipo compra: {$this->descripcion}";
    }
    
    public function getAutorizadoCompra() {
        if($this->autorizado == 0){
            $autorizadocompra = 'NO';
        }else{
            $autorizadocompra = 'SI';
        }
        return $autorizadocompra;
    }
    public function getCompraAuditada() {
        if($this->auditada == 0){
            $compraauditada = 'NO';
        }else{
            $compraauditada = 'SI';
        }
        return $compraauditada;
    }
    
    public function getImportadoMateriaPrima() {
        if($this->importado == 0){
            $importadomateriaprima = 'NO';
        }else{
            $importadomateriaprima = 'SI';
        }
        return $importadomateriaprima;
    }
}
