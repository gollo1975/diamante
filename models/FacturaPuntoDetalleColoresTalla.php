<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura_punto_detalle_colores_talla".
 *
 * @property int $codigo
 * @property int $id_detalle
 * @property int $id_factura
 * @property int $id_talla
 * @property int $id_color
 * @property int $cantidad_venta
 * @property string $fecha_registro
 *
 * @property FacturaVentaPuntoDetalle $detalle
 * @property FacturaVentaPunto $factura
 * @property Tallas $talla
 * @property Colores $color
 */
class FacturaPuntoDetalleColoresTalla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factura_punto_detalle_colores_talla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_detalle', 'id_factura', 'id_talla', 'id_color', 'cantidad_venta','id_inventario'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['id_detalle'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVentaPuntoDetalle::className(), 'targetAttribute' => ['id_detalle' => 'id_detalle']],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVentaPunto::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
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
            'id_factura' => 'Id Factura',
            'id_talla' => 'Id Talla',
            'id_color' => 'Id Color',
            'cantidad_venta' => 'Cantidad Venta',
            'fecha_registro' => 'Fecha Registro',
            'id_inventario' => 'id_inventario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasOne(FacturaVentaPuntoDetalle::className(), ['id_detalle' => 'id_detalle']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(FacturaVentaPunto::className(), ['id_factura' => 'id_factura']);
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
    public function getInventarioPunto()
    {
        return $this->hasOne(InventarioPuntoVenta::className(), ['id_inventario' => 'id_inventario']);
    }
}
