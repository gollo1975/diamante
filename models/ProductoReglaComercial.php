<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "producto_regla_comercial".
 *
 * @property int $id_regla
 * @property int $id_inventario
 * @property int $limite_venta
 * @property int $limite_presupuesto
 * @property string $fecha_cierre
 * @property string $user_name
 *
 * @property InventarioProductos $inventario
 */
class ProductoReglaComercial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'producto_regla_comercial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'limite_venta', 'limite_presupuesto'], 'integer'],
            [['fecha_cierre','fecha_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_regla' => 'Id Regla',
            'id_inventario' => 'Id Inventario',
            'limite_venta' => 'Limite Venta',
            'limite_presupuesto' => 'Limite Presupuesto',
            'fecha_cierre' => 'Fecha Cierre',
            'user_name' => 'User Name',
            'fecha_registro' => 'fecha_registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
    
    public function getEstadoRegla() {
        if($this->estado_regla == 0){
            $estadoregla = 'SI';
        }else{
            $estadoregla = 'NO';
        }
        return $estadoregla;
    }
}
