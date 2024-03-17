<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auditoria_compras".
 *
 * @property int $id_auditoria
 * @property int $id_orden_compra
 * @property int $id_tipo_orden
 * @property int $id_proveedor
 * @property string $fecha_proceso_compra
 * @property int $numero_orden
 * @property int $cerrar_auditoria
 * @property string $user_name
 *
 * @property AuditoriaCompraDetalles[] $auditoriaCompraDetalles
 * @property OrdenCompra $ordenCompra
 * @property TipoOrdenCompra $tipoOrden
 * @property Proveedor $proveedor
 */
class AuditoriaCompras extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auditoria_compras';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_compra', 'id_tipo_orden', 'id_proveedor', 'numero_orden', 'cerrar_auditoria'], 'integer'],
            [['fecha_proceso_compra','fecha_auditoria'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_orden_compra'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenCompra::className(), 'targetAttribute' => ['id_orden_compra' => 'id_orden_compra']],
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
            'id_auditoria' => 'Id Auditoria',
            'id_orden_compra' => 'Codigo:',
            'id_tipo_orden' => 'Tipo orden:',
            'id_proveedor' => 'Proveedor:',
            'fecha_proceso_compra' => 'Fecha compra:',
            'numero_orden' => 'Numero orden',
            'cerrar_auditoria' => 'Auditoria cerrada:',
            'user_name' => 'Usuario',
            'fecha_auditoria' => 'Fecha auditoria:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuditoriaCompraDetalles()
    {
        return $this->hasMany(AuditoriaCompraDetalles::className(), ['id_auditoria' => 'id_auditoria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenCompra()
    {
        return $this->hasOne(OrdenCompra::className(), ['id_orden_compra' => 'id_orden_compra']);
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
    
    public function getCerrarAuditoria() {
        if($this->cerrar_auditoria == 0){
            $cerrarauditoria = 'NO';
        }else{
            $cerrarauditoria = 'SI';
        }
        return $cerrarauditoria;
        
    }
}
