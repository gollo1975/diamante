<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_color_talla".
 *
 * @property int $id_detalle
 * @property int $id_inventario
 * @property int $id_color
 * @property int $id_talla
 * @property int $cantidad
 * @property int $stock_punto
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property InventarioPuntoVenta $inventario
 * @property Colores $color
 * @property Tallas $talla
 */
class DetalleColorTalla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_color_talla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'id_color', 'id_talla', 'cantidad', 'stock_punto','cerrado','id_punto','codigo_producto'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_color'], 'exist', 'skipOnError' => true, 'targetClass' => Colores::className(), 'targetAttribute' => ['id_color' => 'id_color']],
            [['id_talla'], 'exist', 'skipOnError' => true, 'targetClass' => Tallas::className(), 'targetAttribute' => ['id_talla' => 'id_talla']],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
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
            'id_color' => 'Id Color',
            'id_talla' => 'Id Talla',
            'cantidad' => 'Cantidad',
            'stock_punto' => 'Stock Punto',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'cerrado' => 'Cerrado:',
            'id_punto' => 'id_punto',
            'codigo_producto' => 'codigo_producto',
        ];
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
    public function getPunto()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
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
    
    public function getCerradoDetalle() {
        if($this->cerrado == 0){
            $cerradodetalle = 'NO';
        }else{
            $cerradodetalle = 'SI';
        }
        return $cerradodetalle;
    }
    
    //proceso que agrupa el nombre de la talla.
    public function getNombreTalla(){
         return " Talla: {$this->talla->nombre_talla}";
    }
    
    //proceso que agrupa el nombre del color.
    public function getNombreColor(){
         return " Color: {$this->color->colores}";
    }
}
