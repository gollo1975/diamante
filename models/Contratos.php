<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contratos".
 *
 * @property int $id_contrato
 * @property int $id_empleado
 * @property int $nit_cedula
 * @property int $id_tiempo
 * @property int $id_tipo_contrato
 * @property int $id_cargo
 * @property string $descripcion
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property int $id_tipo_salario
 * @property int $salario
 * @property int $aplica_auxilio_transporte
 * @property string $horario_trabajo
 * @property string $funciones
 * @property int $id_tipo_cotizante
 * @property int $id_subtipo_cotizante
 * @property int $id_configuracion_eps
 * @property int $id_entidad_salud
 * @property int $id_configuracion_pension
 * @property int $id_entidad_pension
 * @property int $id_caja_compensacion
 * @property int $id_cesantia
 * @property int $id_arl
 * @property string $ultimo_pago_nomina
 * @property string $ultima_pago_prima
 * @property string $ultima_pago_cesantia
 * @property string $ultima_pago_vacacion
 * @property int $ibp_cesantia_inicial
 * @property int $ibp_prima_inicial
 * @property int $ibp_recargo_nocturno
 * @property int $id_motivo_terminacion
 * @property int $contrato_activo
 * @property string $codigo_municipio_laboral
 * @property string $codigo_municipio_contratado
 * @property int $id_centro_trabajo
 * @property int $id_grupo_pago
 * @property string $fecha_preaviso
 * @property int $dias_contrato
 * @property int $generar_liquidacion
 * @property string $observacion
 * @property string $user_name
 * @property string $fecha_hora_registro
 * @property string $user_name_editado
 * @property string $fecha_hora_editado
 *
 * @property Empleados $empleado
 * @property EntidadSalud $entidadSalud
 * @property ConfiguracionPension $configuracionPension
 * @property EntidadPension $entidadPension
 * @property CajaCompensacion $cajaCompensacion
 * @property EntidadCesantias $cesantia
 * @property Arl $arl
 * @property MotivoTerminacion $motivoTerminacion
 * @property Cargos $cargo
 * @property GrupoPago $grupoPago
 * @property Municipios $codigoMunicipioContratado
 * @property TiempoServicio $tiempo
 * @property Municipios $codigoMunicipioLaboral
 * @property TipoContrato $tipoContrato
 * @property TipoSalario $tipoSalario
 * @property TipoSalario $tipoSalario0
 * @property TipoCotizante $tipoCotizante
 * @property SubtipoCotizante $subtipoCotizante
 * @property ConfiguracionEps $configuracionEps
 */
class Contratos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contratos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'id_tiempo', 'id_tipo_contrato', 'id_cargo', 'fecha_inicio', 'id_tipo_salario', 'salario', 'aplica_auxilio_transporte', 'horario_trabajo', 'id_tipo_cotizante', 'id_subtipo_cotizante', 'id_configuracion_eps', 'id_entidad_salud', 'id_configuracion_pension', 'id_entidad_pension', 'id_caja_compensacion', 'id_cesantia', 'id_arl', 'codigo_municipio_laboral', 'codigo_municipio_contratado', 'id_centro_trabajo', 'id_grupo_pago'], 'required'],
            [['id_empleado', 'nit_cedula', 'id_tiempo', 'id_tipo_contrato', 'id_cargo', 'id_tipo_salario', 'salario', 'aplica_auxilio_transporte', 'id_tipo_cotizante', 'id_subtipo_cotizante', 'id_configuracion_eps', 'id_entidad_salud', 'id_configuracion_pension', 'id_entidad_pension', 'id_caja_compensacion', 'id_cesantia', 'id_arl', 'ibp_cesantia_inicial', 'ibp_prima_inicial', 'ibp_recargo_nocturno', 'id_motivo_terminacion', 'contrato_activo', 'id_centro_trabajo', 'id_grupo_pago', 'dias_contrato', 'generar_liquidacion'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'ultimo_pago_nomina', 'ultima_pago_prima', 'ultima_pago_cesantia', 'ultima_pago_vacacion', 'fecha_preaviso', 'fecha_hora_registro', 'fecha_hora_editado'], 'safe'],
            [['descripcion', 'funciones'], 'string', 'max' => 100],
            [['horario_trabajo', 'codigo_municipio_laboral', 'codigo_municipio_contratado'], 'string', 'max' => 10],
            [['observacion'], 'string', 'max' => 50],
            [['user_name', 'user_name_editado'], 'string', 'max' => 15],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_entidad_salud'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadSalud::className(), 'targetAttribute' => ['id_entidad_salud' => 'id_entidad_salud']],
            [['id_configuracion_pension'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionPension::className(), 'targetAttribute' => ['id_configuracion_pension' => 'id_configuracion_pension']],
            [['id_entidad_pension'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadPension::className(), 'targetAttribute' => ['id_entidad_pension' => 'id_entidad_pension']],
            [['id_caja_compensacion'], 'exist', 'skipOnError' => true, 'targetClass' => CajaCompensacion::className(), 'targetAttribute' => ['id_caja_compensacion' => 'id_caja']],
            [['id_cesantia'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadCesantias::className(), 'targetAttribute' => ['id_cesantia' => 'id_cesantia']],
            [['id_arl'], 'exist', 'skipOnError' => true, 'targetClass' => Arl::className(), 'targetAttribute' => ['id_arl' => 'id_arl']],
            [['id_motivo_terminacion'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoTerminacion::className(), 'targetAttribute' => ['id_motivo_terminacion' => 'id_motivo_terminacion']],
            [['id_cargo'], 'exist', 'skipOnError' => true, 'targetClass' => Cargos::className(), 'targetAttribute' => ['id_cargo' => 'id_cargo']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['codigo_municipio_contratado'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio_contratado' => 'codigo_municipio']],
            [['id_tiempo'], 'exist', 'skipOnError' => true, 'targetClass' => TiempoServicio::className(), 'targetAttribute' => ['id_tiempo' => 'id_tiempo']],
            [['codigo_municipio_laboral'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio_laboral' => 'codigo_municipio']],
            [['id_tipo_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => TipoContrato::className(), 'targetAttribute' => ['id_tipo_contrato' => 'id_tipo_contrato']],
            [['id_tipo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSalario::className(), 'targetAttribute' => ['id_tipo_salario' => 'id_tipo_salario']],
            [['id_tipo_cotizante'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCotizante::className(), 'targetAttribute' => ['id_tipo_cotizante' => 'id_tipo_cotizante']],
            [['id_subtipo_cotizante'], 'exist', 'skipOnError' => true, 'targetClass' => SubtipoCotizante::className(), 'targetAttribute' => ['id_subtipo_cotizante' => 'id_subtipo_cotizante']],
            [['id_configuracion_eps'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionEps::className(), 'targetAttribute' => ['id_configuracion_eps' => 'id_configuracion_eps']],
            [['id_centro_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => CentroTrabajo::className(), 'targetAttribute' => ['id_centro_trabajo' => 'id_centro_trabajo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_contrato' => 'Codigo:',
            'id_empleado' => 'Empleado:',
            'nit_cedula' => 'Documento',
            'id_tiempo' => 'Tiempo servicio:',
            'id_tipo_contrato' => 'Tipo de contrato:',
            'id_cargo' => 'Cargo:',
            'descripcion' => 'Descripcion:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_final' => 'Fecha final:',
            'id_tipo_salario' => 'Tipo salario:',
            'salario' => 'Salario:',
            'aplica_auxilio_transporte' => 'Aplica auxilio transporte:',
            'horario_trabajo' => 'Horario trabajo:',
            'funciones' => 'Funciones:',
            'id_tipo_cotizante' => 'Tipo cotizante:',
            'id_subtipo_cotizante' => 'Subtipo cotizante:',
            'id_configuracion_eps' => 'Configuracion Eps:',
            'id_entidad_salud' => 'Entidad de salud:',
            'id_configuracion_pension' => 'Configuracion pension:',
            'id_entidad_pension' => 'Entidad de pension:',
            'id_caja_compensacion' => 'Caja de compensacion:',
            'id_cesantia' => 'Fondo de cesantia:',
            'id_arl' => 'Nivel arl:',
            'ultimo_pago_nomina' => 'Ultimo pago nomina:',
            'ultima_pago_prima' => 'Ultima pago prima:',
            'ultima_pago_cesantia' => 'Ultima pago cesantia:',
            'ultima_pago_vacacion' => 'Ultima pago vacacion:',
            'ibp_cesantia_inicial' => 'Ibp cesantia inicial',
            'ibp_prima_inicial' => 'Ibp Prima Inicial',
            'ibp_recargo_nocturno' => 'Ibp Recargo Nocturno',
            'id_motivo_terminacion' => 'Motivo terminacion:',
            'contrato_activo' => 'Activo:',
            'codigo_municipio_laboral' => 'Municipio laboral:',
            'codigo_municipio_contratado' => 'Municipio contratado:',
            'id_centro_trabajo' => 'Centro de trabajo:',
            'id_grupo_pago' => 'Grupo pago:',
            'fecha_preaviso' => 'Fecha preaviso:',
            'dias_contrato' => 'Dias del contrato: ',
            'generar_liquidacion' => 'Generar liquidacion:',
            'observacion' => 'Observacion:',
            'user_name' => 'User Name',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'user_name_editado' => 'User Name Editado',
            'fecha_hora_editado' => 'Fecha Hora Editado',
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
    public function getEntidadSalud()
    {
        return $this->hasOne(EntidadSalud::className(), ['id_entidad_salud' => 'id_entidad_salud']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionPension()
    {
        return $this->hasOne(ConfiguracionPension::className(), ['id_configuracion_pension' => 'id_configuracion_pension']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidadPension()
    {
        return $this->hasOne(EntidadPension::className(), ['id_entidad_pension' => 'id_entidad_pension']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCajaCompensacion()
    {
        return $this->hasOne(CajaCompensacion::className(), ['id_caja' => 'id_caja_compensacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCesantia()
    {
        return $this->hasOne(EntidadCesantias::className(), ['id_cesantia' => 'id_cesantia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArl()
    {
        return $this->hasOne(Arl::className(), ['id_arl' => 'id_arl']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivoTerminacion()
    {
        return $this->hasOne(MotivoTerminacion::className(), ['id_motivo_terminacion' => 'id_motivo_terminacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargo()
    {
        return $this->hasOne(Cargos::className(), ['id_cargo' => 'id_cargo']);
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
    public function getCodigoMunicipioContratado()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio_contratado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiempo()
    {
        return $this->hasOne(TiempoServicio::className(), ['id_tiempo' => 'id_tiempo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipioLaboral()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio_laboral']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoContrato()
    {
        return $this->hasOne(TipoContrato::className(), ['id_tipo_contrato' => 'id_tipo_contrato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoSalario()
    {
        return $this->hasOne(TipoSalario::className(), ['id_tipo_salario' => 'id_tipo_salario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoCotizante()
    {
        return $this->hasOne(TipoCotizante::className(), ['id_tipo_cotizante' => 'id_tipo_cotizante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubtipoCotizante()
    {
        return $this->hasOne(SubtipoCotizante::className(), ['id_subtipo_cotizante' => 'id_subtipo_cotizante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionEps()
    {
        return $this->hasOne(ConfiguracionEps::className(), ['id_configuracion_eps' => 'id_configuracion_eps']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCentroTrabajo()
    {
        return $this->hasOne(CentroTrabajo::className(), ['id_centro_trabajo' => 'id_centro_trabajo']);
    }
    
    public function getAplicaAuxilio() {
        if($this->aplica_auxilio_transporte == 0){
             $aplicaauxilio = 'NO';
        }else{
            $aplicaauxilio = 'SI';
        }
        return $aplicaauxilio;
    }
    
     public function getActivo() {
        if($this->contrato_activo == 0){
             $contratoactivo = 'SI';
        }else{
            $contratoactivo = 'NO';
        }
        return $contratoactivo;
    }
    
      public function getGeneraProrroga() {
        if($this->genera_prorroga == 0){
             $contratoactivo = 'SI';
        }else{
            $contratoactivo = 'NO';
        }
        return $contratoactivo;
    }
}
