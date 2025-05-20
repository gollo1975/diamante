<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programacion_nomina_detalle".
 *
 * @property int $id_detalle
 * @property int $id_programacion
 * @property int $codigo_salario
 * @property int $horas_periodo
 * @property int $horas_periodo_reales
 * @property int $dias
 * @property int $dias_reales
 * @property int $dias_transporte
 * @property int $factor_dia
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property int $salario_basico
 * @property int $vlr_devengado
 * @property int $vlr_ibc_medio_tiempo
 * @property int $vlr_devengado_no_prestacional
 * @property double $vlr_deduccion
 * @property double $vlr_credito
 * @property double $vlr_hora
 * @property double $vlr_dia
 * @property int $vlr_neto_pagar
 * @property int $descuento_salud
 * @property int $descuento_pension
 * @property int $auxilio_transporte
 * @property int $vlr_licencia
 * @property double $nro_horas
 * @property int $dias_licencia_descontar
 * @property int $vlr_incapacidad
 * @property int $id_incapacidad
 * @property int $nro_horas_incapacidad
 * @property int $dias_incapacidad_descontar
 * @property int $vlr_ajuste_incapacidad
 * @property int $deduccion
 * @property int $id_credito
 * @property int $id_periodo_pago_nomina
 * @property int $id_grupo_pago
 * @property int $dias_salario
 * @property int $dias_descontar_transporte
 * @property int $id_licencia
 * @property int $porcentaje
 * @property int $vlr_licencia_no_pagada
 * @property int $vlr_vacacion
 *
 * @property Credito $credito
 * @property PeriodoPagoNomina $periodoPagoNomina
 * @property GrupoPago $grupoPago
 * @property ProgramacionNomina $programacion
 * @property ConceptoSalarios $codigoSalario
 * @property Incapacidad $incapacidad
 * @property Licencia $licencia
 */
class ProgramacionNominaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programacion_nomina_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programacion', 'codigo_salario', 'horas_periodo', 'horas_periodo_reales', 'dias', 'dias_reales', 'dias_transporte', 'factor_dia', 'salario_basico', 'vlr_devengado', 'valor_tiempo_extra', 'vlr_devengado_no_prestacional',
                'vlr_neto_pagar', 'descuento_salud', 'descuento_pension', 'auxilio_transporte', 'vlr_licencia', 'dias_licencia_descontar', 'vlr_incapacidad', 'id_incapacidad', 'nro_horas_incapacidad', 'dias_incapacidad_descontar',
                'vlr_ajuste_incapacidad', 'deduccion', 'id_credito', 'id_periodo_pago_nomina', 'id_grupo_pago', 'dias_salario', 'dias_descontar_transporte', 'id_licencia', 'porcentaje', 'vlr_licencia_no_pagada', 'vlr_vacacion',
                'aplico_dias_licencia','aplico_dias_incapacidad','descuento_fondo_solidaridad','vlr_ibc_medio_tiempo','id_novedad'], 'integer'],
            [['fecha_desde', 'fecha_hasta'], 'safe'],
            [['vlr_deduccion', 'vlr_credito', 'vlr_hora', 'vlr_dia', 'nro_horas'], 'number'],
            [['id_credito'], 'exist', 'skipOnError' => true, 'targetClass' => Credito::className(), 'targetAttribute' => ['id_credito' => 'id_credito']],
            [['id_periodo_pago_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPagoNomina::className(), 'targetAttribute' => ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_programacion'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramacionNomina::className(), 'targetAttribute' => ['id_programacion' => 'id_programacion']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
            [['id_incapacidad'], 'exist', 'skipOnError' => true, 'targetClass' => Incapacidad::className(), 'targetAttribute' => ['id_incapacidad' => 'id_incapacidad']],
            [['id_licencia'], 'exist', 'skipOnError' => true, 'targetClass' => Licencia::className(), 'targetAttribute' => ['id_licencia' => 'id_licencia_pk']],
            [['id_novedad'], 'exist', 'skipOnError' => true, 'targetClass' => NovedadTiempoExtra::className(), 'targetAttribute' => ['id_novedad' => 'id_novedad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_programacion' => 'Id Programacion',
            'codigo_salario' => 'Codigo Salario',
            'horas_periodo' => 'Horas Periodo',
            'horas_periodo_reales' => 'Horas Periodo Reales',
            'dias' => 'Dias',
            'dias_reales' => 'Dias Reales',
            'dias_transporte' => 'Dias Transporte',
            'factor_dia' => 'Factor Dia',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'salario_basico' => 'Salario Basico',
            'vlr_devengado' => 'Vlr Devengado',
            'valor_tiempo_extra' => 'Valor_tiempo_extra',
            'vlr_devengado_no_prestacional' => 'Vlr Devengado No Prestacional',
            'vlr_deduccion' => 'Vlr Deduccion',
            'vlr_credito' => 'Vlr Credito',
            'vlr_hora' => 'Vlr Hora',
            'vlr_dia' => 'Vlr Dia',
            'vlr_neto_pagar' => 'Vlr Neto Pagar',
            'descuento_salud' => 'Descuento Salud',
            'descuento_pension' => 'Descuento Pension',
            'auxilio_transporte' => 'Auxilio Transporte',
            'vlr_licencia' => 'Vlr Licencia',
            'nro_horas' => 'Nro Horas',
            'dias_licencia_descontar' => 'Dias Licencia Descontar',
            'vlr_incapacidad' => 'Vlr Incapacidad',
            'id_incapacidad' => 'Id Incapacidad',
            'nro_horas_incapacidad' => 'Nro Horas Incapacidad',
            'dias_incapacidad_descontar' => 'Dias Incapacidad Descontar',
            'vlr_ajuste_incapacidad' => 'Vlr Ajuste Incapacidad',
            'deduccion' => 'Deduccion',
            'id_credito' => 'Id Credito',
            'id_periodo_pago_nomina' => 'Id Periodo Pago Nomina',
            'id_grupo_pago' => 'Id Grupo Pago',
            'dias_salario' => 'Dias Salario',
            'dias_descontar_transporte' => 'Dias Descontar Transporte',
            'id_licencia' => 'Id Licencia',
            'porcentaje' => 'Porcentaje',
            'vlr_licencia_no_pagada' => 'Vlr Licencia No Pagada',
            'vlr_vacacion' => 'Vlr Vacacion',
            'aplico_dias_incapacidad' => 'aplico_dias_incapacidad',
            'aplico_dias_licencia' => 'aplico_dias_licencia',
            'descuento_fondo_solidaridad' => 'descuento_fondo_solidaridad',
            'vlr_ibc_medio_tiempo' =>'vlr_ibc_medio_tiempo',
            'id_novedad' => 'id_novedad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredito()
    {
        return $this->hasOne(Credito::className(), ['id_credito' => 'id_credito']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoPagoNomina()
    {
        return $this->hasOne(PeriodoPagoNomina::className(), ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramacion()
    {
        return $this->hasOne(ProgramacionNomina::className(), ['id_programacion' => 'id_programacion']);
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
    public function getIncapacidad()
    {
        return $this->hasOne(Incapacidad::className(), ['id_incapacidad' => 'id_incapacidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicencia()
    {
        return $this->hasOne(Licencia::className(), ['id_licencia_pk' => 'id_licencia']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getNovedadTiempo()
    {
        return $this->hasOne(NovedadTiempoExtra::className(), ['id_novedad' => 'id_novedad']);
    }
}
