<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodo_nomina_electronica".
 *
 * @property int $id_periodo_electronico
 * @property string $fecha_inicio_periodo
 * @property string $fecha_corte_periodo
 * @property int $cantidad_empleados
 * @property string $fecha_registro
 * @property string $user_name
 * @property int $cerrar_proceso
 * @property int $total_nomina
 * @property int $devengado_nomina
 * @property int $deduccion_nomina
 * @property string $nota
 * @property int $type_document_id
 *
 * @property NominaElectronica[] $nominaElectronicas
 * @property NominaElectronicaDetalle[] $nominaElectronicaDetalles
 */
class PeriodoNominaElectronica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_nomina_electronica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_inicio_periodo', 'fecha_corte_periodo'], 'required'],
            [['fecha_inicio_periodo', 'fecha_corte_periodo', 'fecha_registro'], 'safe'],
            [['cantidad_empleados', 'cerrar_proceso', 'total_nomina', 'devengado_nomina', 'deduccion_nomina', 'type_document_id'], 'integer'],
            [['user_name'], 'string', 'max' => 15],
            [['nota'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_periodo_electronico' => 'Id Periodo Electronico',
            'fecha_inicio_periodo' => 'Fecha Inicio Periodo',
            'fecha_corte_periodo' => 'Fecha Corte Periodo',
            'cantidad_empleados' => 'Cantidad Empleados',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'User Name',
            'cerrar_proceso' => 'Cerrar Proceso',
            'total_nomina' => 'Total Nomina',
            'devengado_nomina' => 'Devengado Nomina',
            'deduccion_nomina' => 'Deduccion Nomina',
            'nota' => 'Nota',
            'type_document_id' => 'Type Document ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominaElectronicas()
    {
        return $this->hasMany(NominaElectronica::className(), ['id_periodo_electronico' => 'id_periodo_electronico']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominaElectronicaDetalles()
    {
        return $this->hasMany(NominaElectronicaDetalle::className(), ['id_periodo_electronico' => 'id_periodo_electronico']);
    }
    
    public function getCerradoProceso() {
        
        if($this->cerrar_proceso == 0){
            $cerradoproceso = 'NO';
        }else{
            $cerradoproceso = 'SI';
        }
        return $cerradoproceso;
    }
}
