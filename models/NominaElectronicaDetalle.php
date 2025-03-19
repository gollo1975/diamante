<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nomina_electronica_detalle".
 *
 * @property int $id_detalle
 * @property int $id_nomina_electronica
 * @property int $id_empleado
 * @property int $codigo_salario
 * @property string $descripcion
 * @property int $devengado_deduccion
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property int $devengado
 * @property int $deduccion
 * @property int $total_dias
 * @property int $dias_prima
 * @property int $dias_cesantias
 * @property int $dias_incapacidad
 * @property int $dias_licencia
 * @property int $dias_licencia_noremuneradas
 * @property double $porcentaje
 * @property double $porcentaje_intereses
 * @property int $auxilio_transporte
 * @property int $deduccion_pension
 * @property int $deduccion_eps
 * @property int $deduccion_fondo_solidaridad
 * @property int $valor_pago_prima
 * @property int $valor_pago_cesantias
 * @property int $valor_pago_intereses
 * @property int $valor_pago_incapacidad
 * @property int $valor_pago_licencia
 * @property string $inicio_incapacidad
 * @property string $final_incapacidad
 * @property string $inicio_licencia
 * @property string $final_licencia
 * @property int $id_agrupado
 * @property int $id_periodo_electronico
 * @property int $codigo_incapacidad
 *
 * @property ConceptoSalarios $codigoSalario
 * @property PeriodoNominaElectronica $periodoElectronico
 * @property ConfiguracionIncapacidad $codigoIncapacidad
 * @property AgruparConceptoSalario $agrupado
 * @property NominaElectronica $nominaElectronica
 * @property Empleados $empleado
 */
class NominaElectronicaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nomina_electronica_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_nomina_electronica', 'id_empleado', 'codigo_salario', 'devengado_deduccion', 'devengado', 'deduccion', 'total_dias', 'dias_prima', 'dias_cesantias', 'dias_incapacidad', 'dias_licencia', 'dias_licencia_noremuneradas', 'auxilio_transporte', 'deduccion_pension', 'deduccion_eps', 'deduccion_fondo_solidaridad', 'valor_pago_prima', 'valor_pago_cesantias', 'valor_pago_intereses', 'valor_pago_incapacidad', 'valor_pago_licencia', 'id_agrupado', 'id_periodo_electronico', 'codigo_incapacidad'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'inicio_incapacidad', 'final_incapacidad', 'inicio_licencia', 'final_licencia'], 'safe'],
            [['porcentaje', 'porcentaje_intereses'], 'number'],
            [['descripcion'], 'string', 'max' => 40],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
            [['id_periodo_electronico'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoNominaElectronica::className(), 'targetAttribute' => ['id_periodo_electronico' => 'id_periodo_electronico']],
            [['codigo_incapacidad'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionIncapacidad::className(), 'targetAttribute' => ['codigo_incapacidad' => 'codigo_incapacidad']],
            [['id_agrupado'], 'exist', 'skipOnError' => true, 'targetClass' => AgruparConceptoSalario::className(), 'targetAttribute' => ['id_agrupado' => 'id_agrupado']],
            [['id_nomina_electronica'], 'exist', 'skipOnError' => true, 'targetClass' => NominaElectronica::className(), 'targetAttribute' => ['id_nomina_electronica' => 'id_nomina_electronica']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_nomina_electronica' => 'Id Nomina Electronica',
            'id_empleado' => 'Id Empleado',
            'codigo_salario' => 'Codigo Salario',
            'descripcion' => 'Descripcion',
            'devengado_deduccion' => 'Devengado Deduccion',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'devengado' => 'Devengado',
            'deduccion' => 'Deduccion',
            'total_dias' => 'Total Dias',
            'dias_prima' => 'Dias Prima',
            'dias_cesantias' => 'Dias Cesantias',
            'dias_incapacidad' => 'Dias Incapacidad',
            'dias_licencia' => 'Dias Licencia',
            'dias_licencia_noremuneradas' => 'Dias Licencia Noremuneradas',
            'porcentaje' => 'Porcentaje',
            'porcentaje_intereses' => 'Porcentaje Intereses',
            'auxilio_transporte' => 'Auxilio Transporte',
            'deduccion_pension' => 'Deduccion Pension',
            'deduccion_eps' => 'Deduccion Eps',
            'deduccion_fondo_solidaridad' => 'Deduccion Fondo Solidaridad',
            'valor_pago_prima' => 'Valor Pago Prima',
            'valor_pago_cesantias' => 'Valor Pago Cesantias',
            'valor_pago_intereses' => 'Valor Pago Intereses',
            'valor_pago_incapacidad' => 'Valor Pago Incapacidad',
            'valor_pago_licencia' => 'Valor Pago Licencia',
            'inicio_incapacidad' => 'Inicio Incapacidad',
            'final_incapacidad' => 'Final Incapacidad',
            'inicio_licencia' => 'Inicio Licencia',
            'final_licencia' => 'Final Licencia',
            'id_agrupado' => 'Id Agrupado',
            'id_periodo_electronico' => 'Id Periodo Electronico',
            'codigo_incapacidad' => 'Codigo Incapacidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoElectronico()
    {
        return $this->hasOne(PeriodoNominaElectronica::className(), ['id_periodo_electronico' => 'id_periodo_electronico']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoIncapacidad()
    {
        return $this->hasOne(ConfiguracionIncapacidad::className(), ['codigo_incapacidad' => 'codigo_incapacidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgrupado()
    {
        return $this->hasOne(AgruparConceptoSalario::className(), ['id_agrupado' => 'id_agrupado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominaElectronica()
    {
        return $this->hasOne(NominaElectronica::className(), ['id_nomina_electronica' => 'id_nomina_electronica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
    }
}
