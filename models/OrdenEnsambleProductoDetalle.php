<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_ensamble_producto_detalle".
 *
 * @property int $id_detalle
 * @property int $id_ensamble
 * @property string $codigo_producto
 * @property string $nombre_producto
 * @property int $cantidad_proyectada
 * @property int $cantidad_real
 * @property double $porcentaje_rendimiento
 *
 * @property OrdenEnsambleProducto $ensamble
 */
class OrdenEnsambleProductoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_ensamble_producto_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ensamble', 'cantidad_proyectada', 'cantidad_real'], 'integer'],
            [['porcentaje_rendimiento'], 'number'],
            [['codigo_producto'], 'string', 'max' => 15],
            [['nombre_producto'], 'string', 'max' => 40],
            [['id_ensamble'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenEnsambleProducto::className(), 'targetAttribute' => ['id_ensamble' => 'id_ensamble']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_ensamble' => 'Id Ensamble',
            'codigo_producto' => 'Codigo Producto',
            'nombre_producto' => 'Nombre Producto',
            'cantidad_proyectada' => 'Cantidad Proyectada',
            'cantidad_real' => 'Cantidad Real',
            'porcentaje_rendimiento' => 'Porcentaje Rendimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnsamble()
    {
        return $this->hasOne(OrdenEnsambleProducto::className(), ['id_ensamble' => 'id_ensamble']);
    }
}
