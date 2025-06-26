<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presentacion_kits_detalle".
 *
 * @property int $id_detalle
 * @property int $id_inventario
 * @property int $id_presentacion
 * @property string $fecha_hora_proceso
 * @property string $user_name
 * @property string $observacion
 *
 * @property InventarioProductos $inventario
 * @property PresentacionProducto $presentacion
 */
class PresentacionKitsDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presentacion_kits_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'id_presentacion'], 'integer'],
            [['fecha_hora_proceso'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
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
            'id_inventario' => 'Id Inventario',
            'id_presentacion' => 'Id Presentacion',
            'fecha_hora_proceso' => 'Fecha Hora Proceso',
            'user_name' => 'User Name',
            'observacion' => 'Observacion',
        ];
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
    public function getPresentacion()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }
}
