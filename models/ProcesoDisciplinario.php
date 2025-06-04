<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proceso_disciplinario".
 *
 * @property int $id_proceso
 * @property int $id_empleado
 * @property int $id_contrato
 * @property int $id_tipo_disciplinario
 * @property int $id_motivo
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property string $fecha_hora_proceso
 * @property string $fecha_registro
 * @property string $fecha_falta
 * @property string $fecha_inicio_suspension
 * @property string $fecha_final_suspension
 * @property int $aplica_suspension
 * @property string $descripcion_proceso
 * @property string $user_name
 *
 * @property Empleados $empleado
 * @property Contratos $contrato
 * @property TipoProcesoDisciplinario $tipoDisciplinario
 * @property MotivoDisciplinario $motivo
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 */
class ProcesoDisciplinario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proceso_disciplinario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'id_tipo_disciplinario', 'fecha_registro'], 'required'],
            [['id_empleado', 'id_contrato', 'id_tipo_disciplinario', 'id_motivo', 'aplica_suspension','id_grupo_pago','proceso_cerrado','autorizado','numero_radicado'], 'integer'],
            [['fecha_hora_proceso', 'fecha_registro', 'fecha_falta', 'fecha_inicio_suspension', 'fecha_final_suspension'], 'safe'],
            [['descripcion_proceso','proceso_descargo'], 'string'],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['user_name'], 'string', 'max' => 15],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_tipo_disciplinario'], 'exist', 'skipOnError' => true, 'targetClass' => TipoProcesoDisciplinario::className(), 'targetAttribute' => ['id_tipo_disciplinario' => 'id_tipo_disciplinario']],
            [['id_motivo'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoDisciplinario::className(), 'targetAttribute' => ['id_motivo' => 'id_motivo']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_proceso' => 'Id',
            'id_empleado' => 'Nombre del empleado:',
            'id_contrato' => 'No de contrato',
            'id_tipo_disciplinario' => 'Tipo de proceso disciplinario:',
            'id_motivo' => 'Motivo del proceso:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'fecha_hora_proceso' => 'Fecha hora Proceso:',
            'fecha_registro' => 'Fecha Registro',
            'fecha_falta' => 'Fecha de la falta:',
            'fecha_inicio_suspension' => 'Fecha inicio suspension:',
            'fecha_final_suspension' => 'Fecha final suspension:',
            'aplica_suspension' => 'Aplica de suspension:',
            'descripcion_proceso' => 'Nota:',
            'user_name' => 'User Name',
            'id_grupo_pago' => 'Grupo de pago:',
            'proceso_cerrado' => 'Proceso cerrado:',
            'autorizado' => 'autorizado',
            'numero_radicado' => 'Numero radicado:',
            'proceso_descargo' => 'Descargos:',
             
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
    public function getContrato()
    {
        return $this->hasOne(Contratos::className(), ['id_contrato' => 'id_contrato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDisciplinario()
    {
        return $this->hasOne(TipoProcesoDisciplinario::className(), ['id_tipo_disciplinario' => 'id_tipo_disciplinario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivo()
    {
        return $this->hasOne(MotivoDisciplinario::className(), ['id_motivo' => 'id_motivo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoDepartamento()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipio()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }
    
    //procesos alternos
    public function getProcesoCerrado() {
        if($this->proceso_cerrado == 0){
            $procesocerrado = 'NO';
        }else{
            $procesocerrado = 'SI';
        }
        return $procesocerrado;
    }
    
    //procesos alternos
    public function getProcesoAutorizado() {
        if($this->autorizado == 0){
            $procesoautorizado = 'NO';
        }else{
            $procesoautorizado = 'SI';
        }
        return $procesoautorizado;
    }
     //procesos alternos
    public function getAplicaSuspension() {
        if($this->aplica_suspension == 0){
            $aplicasuspension = 'NO';
        }else{
            $aplicasuspension = 'SI';
        }
        return $aplicasuspension;
    }
}
