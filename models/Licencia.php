<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "licencia".
 *
 * @property int $id_licencia_pk
 * @property int $codigo_licencia
 * @property int $id_empleado
 * @property int $identificacion
 * @property int $id_contrato
 * @property int $id_grupo_pago
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property string $fecha_proceso
 * @property string $fecha_aplicacion
 * @property double $vlr_pagar_administradora
 * @property double $vlr_licencia
 * @property int $dias_licencia
 * @property int $afecta_transporte
 * @property int $cobrar_administradora
 * @property int $aplicar_adicional
 * @property int $pagar_empleado
 * @property int $pagar_parafiscal
 * @property int $pagar_arl
 * @property int $salario
 * @property string $observacion
 * @property string $user_name
 *
 * @property ConfiguracionLicencia $codigoLicencia
 * @property Empleados $empleado
 * @property Contratos $contrato
 * @property GrupoPago $grupoPago
 */
class Licencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'licencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_licencia', 'id_empleado', 'fecha_desde', 'fecha_hasta'], 'required'],
            [['codigo_licencia', 'id_empleado', 'identificacion', 'id_contrato', 'id_grupo_pago', 'dias_licencia', 'afecta_transporte', 'cobrar_administradora', 'aplicar_adicional', 'pagar_empleado', 'pagar_parafiscal', 'pagar_arl', 'salario'], 'integer'],
            [['fecha_desde', 'fecha_hasta', 'fecha_proceso', 'fecha_aplicacion'], 'safe'],
            [['vlr_pagar_administradora', 'vlr_licencia'], 'number'],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_licencia'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionLicencia::className(), 'targetAttribute' => ['codigo_licencia' => 'codigo_licencia']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_licencia_pk' => 'Id',
            'codigo_licencia' => 'Tipo de licencia',
            'id_empleado' => 'Empleado',
            'identificacion' => 'Documento',
            'id_contrato' => 'No de contrato',
            'id_grupo_pago' => 'Grupo de pago',
            'fecha_desde' => 'Desde',
            'fecha_hasta' => 'Hasta',
            'fecha_proceso' => 'Fecha Proceso',
            'fecha_aplicacion' => 'Fecha Aplicacion',
            'vlr_pagar_administradora' => 'Vlr Pagar Administradora',
            'vlr_licencia' => 'Vlr Licencia',
            'dias_licencia' => 'Dias Licencia',
            'afecta_transporte' => 'Afecta Transporte',
            'cobrar_administradora' => 'Cobrar Administradora',
            'aplicar_adicional' => 'Aplicar Adicional',
            'pagar_empleado' => 'Pagar Empleado',
            'pagar_parafiscal' => 'Pagar Parafiscal',
            'pagar_arl' => 'Pagar Arl',
            'salario' => 'Salario',
            'observacion' => 'Observacion',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoLicencia()
    {
        return $this->hasOne(ConfiguracionLicencia::className(), ['codigo_licencia' => 'codigo_licencia']);
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
    
    public function getafectatransporte(){
        if($this->afecta_transporte == 1){
            $afectatransporte = "SI";
            
        }else{
           $afectatransporte = "NO"; 
        }
        return $afectatransporte;
    }
    
    public function getcobraradministradora(){
        if($this->cobrar_administradora == 1){
            $cobraradministradora= "SI";
            
        }else{
           $cobraradministradora = "NO"; 
        }
        return $cobraradministradora;
    }
    
    public function getaplicaradicional(){
        if($this->aplicar_adicional == 1){
            $aplicaradicional= "SI";
            
        }else{
           $aplicaradicional = "NO"; 
        }
        return $aplicaradicional;
    }
   
    public function getpagarempleado(){
        if($this->pagar_empleado == 1){
            $pagarempleado= "SI";
            
        }else{
           $pagarempleado = "NO"; 
        }
        return $pagarempleado;
    }
}
