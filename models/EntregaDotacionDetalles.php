<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrega_dotacion_detalles".
 *
 * @property int $id
 * @property int $id_entrega
 * @property int $id_inventario
 * @property int $cantidad
 * @property string $talla
 *
 * @property EntregaDotacion $entrega
 * @property InventarioProductos $inventario
 */
class EntregaDotacionDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrega_dotacion_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrega', 'id_inventario'], 'required'],
            [['id_entrega', 'id_inventario', 'cantidad','descargado'], 'integer'],
            [['talla'], 'string', 'max' => 10],
            [['id_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => EntregaDotacion::className(), 'targetAttribute' => ['id_entrega' => 'id_entrega']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_entrega' => 'Id Entrega',
            'id_inventario' => 'Id Inventario',
            'cantidad' => 'Cantidad',
            'talla' => 'Talla',
            'descargado' => 'Pendiente'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntrega()
    {
        return $this->hasOne(EntregaDotacion::className(), ['id_entrega' => 'id_entrega']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
