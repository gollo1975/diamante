<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago_adicional_fecha".
 *
 * @property int $id_pago_fecha
 * @property string $fecha_corte
 * @property string $fecha_hora_creacion
 * @property string $detalle
 * @property int $estado_registro
 * @property string $user_name
 */
class PagoAdicionalFecha extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_adicional_fecha';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_corte'], 'required'],
            [['fecha_corte', 'fecha_hora_creacion'], 'safe'],
            [['estado_registro'], 'integer'],
            [['detalle'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pago_fecha' => 'Id Pago Fecha',
            'fecha_corte' => 'Fecha Corte',
            'fecha_hora_creacion' => 'Fecha Hora Creacion',
            'detalle' => 'Detalle',
            'estado_registro' => 'Estado Registro',
            'user_name' => 'User Name',
        ];
    }
    
    public function getEstadoRegistro(){
        if($this->estado_registro == 0){
            $estadoregistro = "ABIERTO";
        }else{
            $estadoregistro = "CERRADO";
        }
        return $estadoregistro;
    }
}
