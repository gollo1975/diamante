<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario_precio_venta".
 *
 * @property int $consecutivo
 * @property int $id_inventario
 * @property int $precio_venta_publico
 * @property int $iva_incluido
 * @property string $user_name
 * @property string $user_name_editado
 * @property string $fecha_editado
 * @property string $fecha_registro
 * @property int $id_posicion
 *
 * @property InventarioProductos $inventario
 * @property PosicionPrecio $posicion
 */
class InventarioPrecioVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario_precio_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'id_posicion'], 'required'],
            [['id_inventario', 'precio_venta_publico', 'iva_incluido', 'id_posicion'], 'integer'],
            [['fecha_editado', 'fecha_registro'], 'safe'],
            [['user_name', 'user_name_editado'], 'string', 'max' => 15],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_posicion'], 'exist', 'skipOnError' => true, 'targetClass' => PosicionPrecio::className(), 'targetAttribute' => ['id_posicion' => 'id_posicion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consecutivo' => 'Consecutivo',
            'id_inventario' => 'Id Inventario',
            'precio_venta_publico' => 'Precio Venta Publico',
            'iva_incluido' => 'Iva Incluido',
            'user_name' => 'User Name',
            'user_name_editado' => 'User Name Editado',
            'fecha_editado' => 'Fecha Editado',
            'fecha_registro' => 'Fecha Registro',
            'id_posicion' => 'Id Posicion',
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
    public function getPosicion()
    {
        return $this->hasOne(PosicionPrecio::className(), ['id_posicion' => 'id_posicion']);
    }
    
    public function getIvaIncluido() {
        if($this->iva_incluido == 0 ){
            $ivaincluido = 'Seleccione';
        }else{
            if($this->iva_incluido == 1 ){
              $ivaincluido = 'SI';
            }else{
              $ivaincluido = 'NO';  
            }  
        }
        return $ivaincluido;
    }
}
