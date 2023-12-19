<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "regla_descuento_distribuidor".
 *
 * @property int $id_regla
 * @property int $id_inventario
 * @property string $fecha_inicio
 * @property int $fecha_final
 * @property int $tipo_descuento
 * @property int $nuevo_valor
 * @property int $estado_regla
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property InventarioProductos $inventario
 */
class ReglaDescuentoDistribuidor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regla_descuento_distribuidor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'fecha_final', 'tipo_descuento', 'nuevo_valor', 'estado_regla'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'tipo_descuento'], 'required'],
            [['fecha_inicio', 'fecha_registro'], 'safe'],
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
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'tipo_descuento' => 'Tipo Descuento',
            'nuevo_valor' => 'Nuevo Valor',
            'estado_regla' => 'Estado Regla',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }
}
