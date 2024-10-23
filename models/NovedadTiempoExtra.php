<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novedad_tiempo_extra".
 *
 * @property int $id_novedad
 * @property int $id_empleado
 * @property int $id_programacion
 * @property int $codigo_salario
 * @property string $concepto
 * @property double $porcentaje
 * @property int $id_periodo_pago_nomina
 * @property int $id_grupo_pago
 * @property string $fecha_inicio
 * @property string $fecha_corte
 * @property string $fecha_creacion
 * @property double $vlr_hora
 * @property double $nro_horas
 * @property int $total_novedad
 * @property int $salario_contrato
 * @property string $user_name
 *
 * @property Empleados $empleado
 * @property ProgramacionNomina $programacion
 * @property ConceptoSalarios $codigoSalario
 * @property GrupoPago $grupoPago
 * @property PeriodoPagoNomina $periodoPagoNomina
 */
class NovedadTiempoExtra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'novedad_tiempo_extra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'codigo_salario'], 'required'],
            [['id_empleado', 'id_programacion', 'codigo_salario', 'id_periodo_pago_nomina', 'id_grupo_pago', 'total_novedad', 'salario_contrato'], 'integer'],
            [['porcentaje', 'vlr_hora', 'nro_horas'], 'number'],
            [['fecha_inicio', 'fecha_corte', 'fecha_creacion'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_programacion'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramacionNomina::className(), 'targetAttribute' => ['id_programacion' => 'id_programacion']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_periodo_pago_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPagoNomina::className(), 'targetAttribute' => ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_novedad' => 'Id',
            'id_empleado' => 'Empleado',
            'id_programacion' => 'Programacion:',
            'codigo_salario' => 'Concepto de salario',
            'porcentaje' => 'Porcentaje',
            'id_periodo_pago_nomina' => 'Periodo de pago:',
            'id_grupo_pago' => 'Grupo de pago:',
            'fecha_inicio' => 'Desde:',
            'fecha_corte' => 'Hasta:',
            'fecha_creacion' => 'Fecha Creacion',
            'vlr_hora' => 'Vlr Hora',
            'nro_horas' => 'Nro Horas',
            'total_novedad' => 'Total Novedad',
            'salario_contrato' => 'Salario Contrato',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
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
}
