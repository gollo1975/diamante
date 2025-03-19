<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nomina_electronica".
 *
 * @property int $id_nomina_electronica
 * @property int $id_periodo_pago
 * @property int $id_tipo_nomina
 * @property int $id_contrato
 * @property int $id_empleado
 * @property string $codigo_documento
 * @property int $id_periodo_electronico
 * @property int $id_grupo_pago
 * @property int $documento_empleado
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $nombre_completo
 * @property string $email_empleado
 * @property int $salario_contrato
 * @property int $type_worker_id
 * @property int $sub_type_worker_id
 * @property int $codigo_municipio
 * @property string $direccion_empleado
 * @property int $codigo_forma_pago
 * @property string $nombre_banco
 * @property string $nombre_cuenta
 * @property string $numero_cuenta
 * @property string $cune
 * @property string $qrstr
 * @property string $fecha_inicio_nomina
 * @property string $fecha_final_nomina
 * @property string $fecha_inicio_contrato
 * @property string $fecha_terminacion_contrato
 * @property int $dias_trabajados
 * @property string $fecha_envio_nomina
 * @property string $fecha_recepcion_dian
 * @property string $fecha_envio_begranda
 * @property int $total_devengado
 * @property int $total_deduccion
 * @property int $total_pagar
 * @property string $user_name
 * @property int $generado_detalle
 * @property int $exportado_nomina
 * @property int $numero_nomina_electronica
 * @property string $consecutivo
 *
 * @property Contratos $contrato
 * @property Empleados $empleado
 * @property PeriodoNominaElectronica $periodoElectronico
 * @property GrupoPago $grupoPago
 * @property PeriodoPago $periodoPago
 * @property TipoNomina $tipoNomina
 * @property NominaElectronicaDetalle[] $nominaElectronicaDetalles
 */
class NominaElectronica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nomina_electronica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_periodo_pago', 'id_tipo_nomina', 'id_contrato', 'id_empleado', 'id_periodo_electronico', 'id_grupo_pago', 'documento_empleado', 'salario_contrato', 'type_worker_id', 'sub_type_worker_id', 'codigo_municipio', 'codigo_forma_pago', 'dias_trabajados', 'total_devengado', 'total_deduccion', 'total_pagar', 'generado_detalle', 'exportado_nomina', 'numero_nomina_electronica'], 'integer'],
            [['fecha_inicio_nomina', 'fecha_final_nomina', 'fecha_inicio_contrato', 'fecha_terminacion_contrato', 'fecha_envio_nomina', 'fecha_recepcion_dian', 'fecha_envio_begranda'], 'safe'],
            [['codigo_documento', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'consecutivo'], 'string', 'max' => 10],
            [['nombre_completo', 'direccion_empleado'], 'string', 'max' => 50],
            [['email_empleado'], 'string', 'max' => 60],
            [['nombre_banco'], 'string', 'max' => 40],
            [['nombre_cuenta', 'numero_cuenta'], 'string', 'max' => 20],
            [['cune'], 'string', 'max' => 350],
            [['qrstr'], 'string', 'max' => 2000],
            [['user_name'], 'string', 'max' => 15],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_periodo_electronico'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoNominaElectronica::className(), 'targetAttribute' => ['id_periodo_electronico' => 'id_periodo_electronico']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_periodo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPago::className(), 'targetAttribute' => ['id_periodo_pago' => 'id_periodo_pago']],
            [['id_tipo_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => TipoNomina::className(), 'targetAttribute' => ['id_tipo_nomina' => 'id_tipo_nomina']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_nomina_electronica' => 'Id Nomina Electronica',
            'id_periodo_pago' => 'Id Periodo Pago',
            'id_tipo_nomina' => 'Id Tipo Nomina',
            'id_contrato' => 'Id Contrato',
            'id_empleado' => 'Id Empleado',
            'codigo_documento' => 'Codigo Documento',
            'id_periodo_electronico' => 'Id Periodo Electronico',
            'id_grupo_pago' => 'Id Grupo Pago',
            'documento_empleado' => 'Documento Empleado',
            'primer_nombre' => 'Primer Nombre',
            'segundo_nombre' => 'Segundo Nombre',
            'primer_apellido' => 'Primer Apellido',
            'segundo_apellido' => 'Segundo Apellido',
            'nombre_completo' => 'Nombre Completo',
            'email_empleado' => 'Email Empleado',
            'salario_contrato' => 'Salario Contrato',
            'type_worker_id' => 'Type Worker ID',
            'sub_type_worker_id' => 'Sub Type Worker ID',
            'codigo_municipio' => 'Codigo Municipio',
            'direccion_empleado' => 'Direccion Empleado',
            'codigo_forma_pago' => 'Codigo Forma Pago',
            'nombre_banco' => 'Nombre Banco',
            'nombre_cuenta' => 'Nombre Cuenta',
            'numero_cuenta' => 'Numero Cuenta',
            'cune' => 'Cune',
            'qrstr' => 'Qrstr',
            'fecha_inicio_nomina' => 'Fecha Inicio Nomina',
            'fecha_final_nomina' => 'Fecha Final Nomina',
            'fecha_inicio_contrato' => 'Fecha Inicio Contrato',
            'fecha_terminacion_contrato' => 'Fecha Terminacion Contrato',
            'dias_trabajados' => 'Dias Trabajados',
            'fecha_envio_nomina' => 'Fecha Envio Nomina',
            'fecha_recepcion_dian' => 'Fecha Recepcion Dian',
            'fecha_envio_begranda' => 'Fecha Envio Begranda',
            'total_devengado' => 'Total Devengado',
            'total_deduccion' => 'Total Deduccion',
            'total_pagar' => 'Total Pagar',
            'user_name' => 'User Name',
            'generado_detalle' => 'Generado Detalle',
            'exportado_nomina' => 'Exportado Nomina',
            'numero_nomina_electronica' => 'Numero Nomina Electronica',
            'consecutivo' => 'Consecutivo',
        ];
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
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoPago()
    {
        return $this->hasOne(PeriodoPago::className(), ['id_periodo_pago' => 'id_periodo_pago']);
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
    public function getNominaElectronicaDetalles()
    {
        return $this->hasMany(NominaElectronicaDetalle::className(), ['id_nomina_electronica' => 'id_nomina_electronica']);
    }
}
