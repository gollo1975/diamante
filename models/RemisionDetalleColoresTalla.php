<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remision_detalle_colores_talla".
 *
 * @property int $codigo
 * @property int $id_detalle
 * @property int $id_remision
 * @property int $id_talla
 * @property int $id_color
 * @property int $id_inventario
 * @property int $cantidad_venta
 * @property string $fecha_registro
 *
 * @property RemisionDetalles $detalle
 * @property Remisiones $remision
 * @property Tallas $talla
 * @property Colores $color
 * @property InventarioPuntoVenta $inventario
 */
class RemisionDetalleColoresTalla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remision_detalle_colores_talla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_detalle', 'id_remision', 'id_talla', 'id_color', 'id_inventario', 'cantidad_venta'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['id_detalle'], 'exist', 'skipOnError' => true, 'targetClass' => RemisionDetalles::className(), 'targetAttribute' => ['id_detalle' => 'id_detalle']],
            [['id_remision'], 'exist', 'skipOnError' => true, 'targetClass' => Remisiones::className(), 'targetAttribute' => ['id_remision' => 'id_remision']],
            [['id_talla'], 'exist', 'skipOnError' => true, 'targetClass' => Tallas::className(), 'targetAttribute' => ['id_talla' => 'id_talla']],
            [['id_color'], 'exist', 'skipOnError' => true, 'targetClass' => Colores::className(), 'targetAttribute' => ['id_color' => 'id_color']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'id_detalle' => 'Id Detalle',
            'id_remision' => 'Id Remision',
            'id_talla' => 'Id Talla',
            'id_color' => 'Id Color',
            'id_inventario' => 'Id Inventario',
            'cantidad_venta' => 'Cantidad Venta',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasOne(RemisionDetalles::className(), ['id_detalle' => 'id_detalle']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemision()
    {
        return $this->hasOne(Remisiones::className(), ['id_remision' => 'id_remision']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalla()
    {
        return $this->hasOne(Tallas::className(), ['id_talla' => 'id_talla']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Colores::className(), ['id_color' => 'id_color']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioPuntoVenta::className(), ['id_inventario' => 'id_inventario']);
    }
}
