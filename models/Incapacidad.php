<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incapacidad".
 *
 * @property int $id_incapacidad
 * @property int $codigo_incapacidad
 * @property int $id_empleado
 * @property int $identificacion
 * @property int $id_contrato
 * @property int $id_grupo_pago
 * @property int $id_codigo
 * @property string $codigo_diagnostico
 * @property int $numero_incapacidad
 * @property string $nombre_medico
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property string $fecha_documento_fisico
 * @property string $fecha_aplicacion
 * @property int $transcripcion
 * @property int $cobrar_administradora
 * @property int $aplicar_adicional
 * @property string $fecha_creacion
 * @property int $dias_incapacidad
 * @property int $salario_mes_anterior
 * @property int $salario
 * @property int $vlr_liquidado
 * @property double $porcentaje_pago
 * @property int $dias_cobro_eps
 * @property int $vlr_cobro_administradora
 * @property int $pagar_empleado
 * @property int $vlr_saldo_administradora
 * @property int $id_entidad_salud
 * @property int $prorroga
 * @property string $fecha_inicio_empresa
 * @property string $fecha_final_empresa
 * @property string $fecha_inicio_administradora
 * @property string $fecha_final_administradora
 * @property int $dias_administradora
 * @property int $dias_empresa
 * @property int $vlr_pago_empresa
 * @property int $ibc_total_incapacidad
 * @property int $dias_acumulados
 * @property double $vlr_hora
 * @property string $user_name
 * @property string $observacion
 * @property int $estado_incapacidad_adicional
 *
 * @property ConfiguracionIncapacidad $codigoIncapacidad
 * @property Empleados $empleado
 * @property Contratos $contrato
 * @property GrupoPago $grupoPago
 * @property DiagnosticoIncapacidad $codigo
 * @property EntidadSalud $entidadSalud
 */
class Incapacidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incapacidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_incapacidad', 'id_empleado', 'id_codigo', 'numero_incapacidad', 'fecha_inicio', 'fecha_final', 'fecha_documento_fisico', 'fecha_aplicacion'], 'required'],
            [['codigo_incapacidad', 'id_empleado', 'identificacion', 'id_contrato', 'id_grupo_pago', 'id_codigo', 'numero_incapacidad', 'transcripcion', 'cobrar_administradora', 'aplicar_adicional', 'dias_incapacidad', 'salario_mes_anterior', 'salario', 'vlr_liquidado', 'dias_cobro_eps', 'vlr_cobro_administradora', 'pagar_empleado', 'vlr_saldo_administradora', 'id_entidad_salud', 'prorroga', 'dias_administradora', 'dias_empresa', 'vlr_pago_empresa', 'ibc_total_incapacidad', 'dias_acumulados', 'estado_incapacidad_adicional'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'fecha_documento_fisico', 'fecha_aplicacion', 'fecha_creacion', 'fecha_inicio_empresa', 'fecha_final_empresa', 'fecha_inicio_administradora', 'fecha_final_administradora'], 'safe'],
            [['porcentaje_pago', 'vlr_hora'], 'number'],
            [['observacion'], 'string'],
            [['codigo_diagnostico'], 'string', 'max' => 10],
            [['nombre_medico'], 'string', 'max' => 50],
            [['user_name','user_name_editado'], 'string', 'max' => 15],
            [['codigo_incapacidad'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionIncapacidad::className(), 'targetAttribute' => ['codigo_incapacidad' => 'codigo_incapacidad']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => DiagnosticoIncapacidad::className(), 'targetAttribute' => ['id_codigo' => 'id_codigo']],
            [['id_entidad_salud'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadSalud::className(), 'targetAttribute' => ['id_entidad_salud' => 'id_entidad_salud']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_incapacidad' => 'Id',
            'codigo_incapacidad' => 'Codigo',
            'id_empleado' => 'Empleado',
            'identificacion' => 'Documento',
            'id_contrato' => 'No contrato',
            'id_grupo_pago' => 'Grupo de pago',
            'id_codigo' => 'Dianostico',
            'codigo_diagnostico' => 'Codigo',
            'numero_incapacidad' => 'Numero incapacidad',
            'nombre_medico' => 'Profesional',
            'fecha_inicio' => 'Fecha inicio',
            'fecha_final' => 'Fecha final',
            'fecha_documento_fisico' => 'Fecha documento fisico',
            'fecha_aplicacion' => 'Fecha aplicacion',
            'transcripcion' => 'Transcripcion',
            'cobrar_administradora' => 'Cobrar',
            'aplicar_adicional' => 'Aplicar adicional',
            'fecha_creacion' => 'Fecha Creacion',
            'dias_incapacidad' => 'Dias Incapacidad',
            'salario_mes_anterior' => 'Salario Mes Anterior',
            'salario' => 'Salario',
            'vlr_liquidado' => 'Vlr Liquidado',
            'porcentaje_pago' => 'Porcentaje Pago',
            'dias_cobro_eps' => 'Dias Cobro Eps',
            'vlr_cobro_administradora' => 'Vlr Cobro Administradora',
            'pagar_empleado' => 'Pagar Empleado',
            'vlr_saldo_administradora' => 'Vlr Saldo Administradora',
            'id_entidad_salud' => 'Eps',
            'prorroga' => 'Prorroga',
            'fecha_inicio_empresa' => 'Fecha Inicio Empresa',
            'fecha_final_empresa' => 'Fecha Final Empresa',
            'fecha_inicio_administradora' => 'Fecha Inicio Administradora',
            'fecha_final_administradora' => 'Fecha Final Administradora',
            'dias_administradora' => 'Dias Administradora',
            'dias_empresa' => 'Dias Empresa',
            'vlr_pago_empresa' => 'Vlr Pago Empresa',
            'ibc_total_incapacidad' => 'Ibc Total Incapacidad',
            'dias_acumulados' => 'Dias Acumulados',
            'vlr_hora' => 'Vlr Hora',
            'user_name' => 'User Name',
            'user_name_editado' => 'user_name_editado',
            'observacion' => 'Observacion',
            'estado_incapacidad_adicional' => 'Estado Incapacidad Adicional',
        ];
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
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
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
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigo()
    {
        return $this->hasOne(DiagnosticoIncapacidad::className(), ['id_codigo' => 'id_codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidadSalud()
    {
        return $this->hasOne(EntidadSalud::className(), ['id_entidad_salud' => 'id_entidad_salud']);
    }
    
    public function getCobraradministradora(){
         if($this->cobrar_administradora == 1){
            $cobraradministradora = "SI";
        }else{
            $cobraradministradora = "NO";
        }
        return $cobraradministradora;
    }
    public function getAplicaradicional(){
         if($this->aplicar_adicional == 1){
            $aplicaradicional = "SI";
        }else{
            $aplicaradicional = "NO";
        }
        return $aplicaradicional;
    }
    public function getTranscripcionincapacidad(){
         if($this->transcripcion == 1){
            $transcripcionincapacidad = "SI";
        }else{
            $transcripcionincapacidad = "NO";
        }
        return $transcripcionincapacidad;
    }
     public function getPagarempleado(){
         if($this->pagar_empleado == 1){
            $pagarempleado = "SI";
        }else{
            $pagarempleado = "NO";
        }
        return $pagarempleado;
    }
    public function getProrrogaIncapacidad(){
         if($this->prorroga == 1){
            $prorrogaincapacidad = "SI";
        }else{
            $prorrogaincapacidad = "NO";
        }
        return $prorrogaincapacidad;
    }
}
