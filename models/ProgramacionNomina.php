<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programacion_nomina".
 *
 * @property int $id_programacion
 * @property int $id_grupo_pago
 * @property int $id_periodo_pago_nomina
 * @property int $id_tipo_nomina
 * @property int $id_contrato
 * @property int $id_empleado
 * @property int $cedula_empleado
 * @property int $salario_contrato
 * @property string $fecha_inicio_contrato
 * @property string $fecha_final_contrato
 * @property string $fecha_ultima_prima
 * @property string $fecha_ultima_cesantia
 * @property string $fecha_ultima_vacacion
 * @property string $fecha_desde
 * @property int $nro_pago
 * @property int $total_devengado
 * @property int $total_pagar
 * @property int $total_deduccion
 * @property int $total_auxilio_transporte
 * @property int $ibc_prestacional
 * @property int $vlr_ibp_medio_tiempo
 * @property int $ibc_no_prestacional
 * @property int $total_licencia
 * @property int $total_incapacidad
 * @property int $ajuste_incapacidad
 * @property double $total_tiempo_extra
 * @property double $total_recargo
 * @property string $fecha_hasta
 * @property string $fecha_real_corte
 * @property string $fecha_creacion
 * @property string $fecha_inicio_vacacion
 * @property string $fecha_final_vacacion
 * @property int $dias_vacacion
 * @property int $horas_vacacion
 * @property int $ibc_vacacion
 * @property int $dias_pago
 * @property int $dia_real_pagado
 * @property double $horas_pago
 * @property int $estado_generado
 * @property int $estado_liquidado
 * @property int $estado_cerrado
 * @property int $factor_dia
 * @property int $salario_medio_tiempo
 * @property int $salario_promedio
 * @property int $dias_ausentes
 * @property int $total_ibc_no_prestacional
 * @property string $user_name
 * @property int $importar_prima
 * @property int $pago_aplicado
 *
 * @property GrupoPago $grupoPago
 * @property PeriodoPagoNomina $periodoPagoNomina
 * @property TipoNomina $tipoNomina
 * @property Contratos $contrato
 * @property Empleados $empleado
 */
class ProgramacionNomina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programacion_nomina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo_pago', 'id_periodo_pago_nomina', 'id_tipo_nomina', 'id_contrato', 'id_empleado', 'cedula_empleado', 'salario_contrato', 'nro_pago', 'total_devengado', 'total_pagar', 'total_deduccion', 'total_auxilio_transporte', 'ibc_prestacional', 'vlr_ibp_medio_tiempo', 
                'ibc_no_prestacional', 'total_licencia', 'total_incapacidad', 'ajuste_incapacidad', 'dias_vacacion', 'horas_vacacion', 'ibc_vacacion', 'dias_pago', 'dia_real_pagado', 'estado_generado', 'estado_liquidado', 'estado_cerrado', 'salario_medio_tiempo',
                'salario_promedio', 'dias_ausentes', 'total_ibc_no_prestacional', 'importar_prima', 'pago_aplicado','documento_detalle_generado','documento_generado','anio'], 'integer'],
            [['fecha_inicio_contrato', 'fecha_final_contrato', 'fecha_ultima_prima', 'fecha_ultima_cesantia', 'fecha_ultima_vacacion', 'fecha_desde', 'fecha_hasta', 'fecha_real_corte', 'fecha_creacion', 'fecha_inicio_vacacion', 'fecha_final_vacacion'], 'safe'],
            [['total_tiempo_extra', 'total_recargo', 'horas_pago','factor_dia'], 'number'],
            [['user_name'], 'string', 'max' => 15],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_periodo_pago_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPagoNomina::className(), 'targetAttribute' => ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']],
            [['id_tipo_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => TipoNomina::className(), 'targetAttribute' => ['id_tipo_nomina' => 'id_tipo_nomina']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_programacion' => 'Id Programacion',
            'id_grupo_pago' => 'Grupo de pago:',
            'id_periodo_pago_nomina' => 'Id Periodo Pago Nomina',
            'id_tipo_nomina' => 'Id Tipo Nomina',
            'id_contrato' => 'Id Contrato',
            'id_empleado' => 'Id Empleado',
            'cedula_empleado' => 'Cedula Empleado',
            'salario_contrato' => 'Salario Contrato',
            'fecha_inicio_contrato' => 'Fecha Inicio Contrato',
            'fecha_final_contrato' => 'Fecha Final Contrato',
            'fecha_ultima_prima' => 'Fecha Ultima Prima',
            'fecha_ultima_cesantia' => 'Fecha Ultima Cesantia',
            'fecha_ultima_vacacion' => 'Fecha Ultima Vacacion',
            'fecha_desde' => 'Fecha Desde',
            'nro_pago' => 'Nro Pago',
            'total_devengado' => 'Total Devengado',
            'total_pagar' => 'Total Pagar',
            'total_deduccion' => 'Total Deduccion',
            'total_auxilio_transporte' => 'Total Auxilio Transporte',
            'ibc_prestacional' => 'Ibc Prestacional',
            'vlr_ibp_medio_tiempo' => 'Vlr Ibp Medio Tiempo',
            'ibc_no_prestacional' => 'Ibc No Prestacional',
            'total_licencia' => 'Total Licencia',
            'total_incapacidad' => 'Total Incapacidad',
            'ajuste_incapacidad' => 'Ajuste Incapacidad',
            'total_tiempo_extra' => 'Total Tiempo Extra',
            'total_recargo' => 'Total Recargo',
            'fecha_hasta' => 'Fecha Hasta',
            'anio' => 'Anio:',
            'fecha_real_corte' => 'Fecha Real Corte',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_inicio_vacacion' => 'Fecha Inicio Vacacion',
            'fecha_final_vacacion' => 'Fecha Final Vacacion',
            'dias_vacacion' => 'Dias Vacacion',
            'horas_vacacion' => 'Horas Vacacion',
            'ibc_vacacion' => 'Ibc Vacacion',
            'dias_pago' => 'Dias de pago',
            'dia_real_pagado' => 'Dia Real Pagado',
            'horas_pago' => 'Horas Pago',
            'estado_generado' => 'Estado Generado',
            'estado_liquidado' => 'Estado Liquidado',
            'estado_cerrado' => 'Estado Cerrado',
            'factor_dia' => 'Factor Dia',
            'salario_medio_tiempo' => 'Salario Medio Tiempo',
            'salario_promedio' => 'Salario Promedio',
            'dias_ausentes' => 'Dias Ausentes',
            'total_ibc_no_prestacional' => 'Total Ibc No Prestacional',
            'user_name' => 'User Name',
            'importar_prima' => 'Importar Prima',
            'pago_aplicado' => 'Pago Aplicado',
        ];
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
    public function getPeriodoPagoNomina()
    {
        return $this->hasOne(PeriodoPagoNomina::className(), ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoNomina()
    {
        return $this->hasOne(TipoNomina::className(), ['id_tipo_nomina' => 'id_tipo_nomina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contratos::className(), ['id_contrato' => 'id_contrato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
    }
}
