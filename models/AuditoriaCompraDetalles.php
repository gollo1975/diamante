<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auditoria_compra_detalles".
 *
 * @property int $id
 * @property int $id_items
 * @property string $nombre_producto
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $nueva_cantidad
 * @property int $nuevo_valor
 * @property string $nota
 * @property int $id_auditoria
 *
 * @property Items $items
 * @property AuditoriaCompras $auditoria
 */
class AuditoriaCompraDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auditoria_compra_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_items', 'cantidad', 'valor_unitario', 'nueva_cantidad', 'nuevo_valor', 'id_auditoria','entrada_salida','estado_producto'], 'integer'],
            [['nombre_producto'], 'string', 'max' => 40],
            [['nota'], 'string', 'max' => 100],
            [['comentario'], 'string'],
            [['id_items'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['id_items' => 'id_items']],
            [['id_auditoria'], 'exist', 'skipOnError' => true, 'targetClass' => AuditoriaCompras::className(), 'targetAttribute' => ['id_auditoria' => 'id_auditoria']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_items' => 'Id Items',
            'nombre_producto' => 'Nombre Producto',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'nueva_cantidad' => 'Nueva Cantidad',
            'nuevo_valor' => 'Nuevo Valor',
            'nota' => 'Nota',
            'id_auditoria' => 'Id Auditoria',
            'entrada_salida' => 'entrada_salida',
            'comentario' => 'comentario',
            'estado_producto' => 'estado_producto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasOne(Items::className(), ['id_items' => 'id_items']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuditoria()
    {
        return $this->hasOne(AuditoriaCompras::className(), ['id_auditoria' => 'id_auditoria']);
    }
    
    public function getEstadoProducto() {
        if($this->estado_producto == 0){
            $estadoproducto = 'BUENO';
        }else{
            $estadoproducto = 'MALO';
        }
        return $estadoproducto;
        
    }
}
