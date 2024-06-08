<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "descuento_punto_venta".
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
 * @property InventarioPuntoVenta $inventario
 */
class DescuentoPuntoVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'descuento_punto_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'tipo_descuento', 'nuevo_valor', 'estado_regla','id_punto'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'fecha_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
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
            'id_punto' => 'Punto de venta:',
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
    public function getPuntoVenta()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
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
            $estadoregla = 'ACTIVO';
        }else{
            $estadoregla = 'INACTIVO';
        }
        return $estadoregla;
    }
}
