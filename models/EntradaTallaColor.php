<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada_talla_color".
 *
 * @property int $id
 * @property int $id_detalle
 * @property int $id_inventario
 * @property int $id_entrada
 * @property int $id_color
 * @property int $id_talla
 * @property int $cantidad
 * @property int $cerrado
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property EntradaProductoInventarioDetalle $detalle
 * @property InventarioPuntoVenta $inventario
 * @property EntradaProductosInventario $entrada
 * @property Colores $color
 * @property Tallas $talla
 */
class EntradaTallaColor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada_talla_color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_detalle', 'id_inventario', 'id_entrada', 'id_color', 'id_talla', 'cantidad', 'cerrado'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_detalle'], 'exist', 'skipOnError' => true, 'targetClass' => EntradaProductoInventarioDetalle::className(), 'targetAttribute' => ['id_detalle' => 'id_detalle']],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_entrada'], 'exist', 'skipOnError' => true, 'targetClass' => EntradaProductosInventario::className(), 'targetAttribute' => ['id_entrada' => 'id_entrada']],
            [['id_color'], 'exist', 'skipOnError' => true, 'targetClass' => Colores::className(), 'targetAttribute' => ['id_color' => 'id_color']],
            [['id_talla'], 'exist', 'skipOnError' => true, 'targetClass' => Tallas::className(), 'targetAttribute' => ['id_talla' => 'id_talla']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_detalle' => 'Id Detalle',
            'id_inventario' => 'Id Inventario',
            'id_entrada' => 'Id Entrada',
            'id_color' => 'Id Color',
            'id_talla' => 'Id Talla',
            'cantidad' => 'Cantidad',
            'cerrado' => 'Cerrado',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasOne(EntradaProductoInventarioDetalle::className(), ['id_detalle' => 'id_detalle']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioPuntoVenta::className(), ['id_inventario' => 'id_inventario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntrada()
    {
        return $this->hasOne(EntradaProductosInventario::className(), ['id_entrada' => 'id_entrada']);
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
    public function getTalla()
    {
        return $this->hasOne(Tallas::className(), ['id_talla' => 'id_talla']);
    }
    public function getCerradaEntrada() {
        if($this->cerrado == 0){
            $cerradaentrada = 'NO';
        }else{
            $cerradaentrada = 'SI';
        }    
        return $cerradaentrada;
    }
}
