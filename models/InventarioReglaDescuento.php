<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario_regla_descuento".
 *
 * @property int $id_regla
 * @property int $id_inventario
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property int $tipo_descuento
 * @property int $nuevo_valor
 * @property int $estado_regla
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property InventarioProductos $inventario
 */
class InventarioReglaDescuento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario_regla_descuento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_inicio', 'fecha_final', 'tipo_descuento', 'nuevo_valor'], 'required'],
            [['id_inventario', 'tipo_descuento', 'nuevo_valor', 'estado_regla'], 'integer'],
            ['nuevo_valor', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['fecha_inicio', 'fecha_final', 'fecha_registro'], 'safe'],
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
            'id_regla' => 'Id',
            'id_inventario' => 'Id Inventario',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_final' => 'Fecha final:',
            'tipo_descuento' => 'Tipo Descuento:',
            'nuevo_valor' => 'Nuevo valor:',
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
    public function getTipoDescuento() {
        if($this->tipo_descuento == 1){
            $tipodescuento = 'PORCENTAJE';
        }else{
            $tipodescuento = 'VALORES';
        }
        return $tipodescuento;
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
