<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agentes_comerciales".
 *
 * @property int $id_agente
 * @property int $id_tipo_documento
 * @property int $documento
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $celular_agente
 * @property string $direccion
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property string $fecha_registro
 * @property string $user_name
 * @property int $id_cargo
 *
 * @property TipoDocumento $tipoDocumento
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 * @property Cargos $cargo
 */
class AgentesComerciales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agentes_comerciales';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->primer_apellido = strtoupper($this->primer_apellido); 
        $this->segundo_apellido = strtoupper($this->segundo_apellido); 
        $this->primer_nombre = strtoupper($this->primer_nombre); 
        $this->segundo_nombre = strtoupper($this->segundo_nombre); 
        $this->direccion = strtoupper($this->direccion); 
        $this->email_agente = strtolower($this->email_agente); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'dv','nit_cedula', 'primer_nombre', 'primer_apellido', 'codigo_departamento', 'codigo_municipio', 'id_cargo','id_coordinador'], 'required'],
            [['id_tipo_documento', 'id_cargo','estado','dv','gestion_diaria','id_coordinador','hacer_pedido','hacer_recibo_caja'], 'integer'],
            [['fecha_registro'], 'safe'],
            ['email_agente', 'email'],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'user_name'], 'string', 'max' => 15],
            [['celular_agente'], 'string', 'max' => 12],
            [['direccion'], 'string', 'max' => 40],
            [['nombre_completo','email_agente','nit_cedula'], 'string'],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_cargo'], 'exist', 'skipOnError' => true, 'targetClass' => Cargos::className(), 'targetAttribute' => ['id_cargo' => 'id_cargo']],
            [['id_coordinador'], 'exist', 'skipOnError' => true, 'targetClass' => Coordinadores::className(), 'targetAttribute' => ['id_coordinador' => 'id_coordinador']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_agente' => 'Código',
            'dv' => 'Dv:',
            'id_tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Documento:',
            'primer_nombre' => 'Primer nombre:',
            'segundo_nombre' => 'Segundo nombre:',
            'primer_apellido' => 'Primer apellido:',
            'segundo_apellido' => 'Segundo apellido:',
            'celular_agente' => 'Celular:',
            'direccion' => 'Direccion:',
            'email_agente' => 'Email:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'fecha_registro' => 'Fecha registro:',
            'user_name' => 'User name:',
            'id_cargo' => 'Cargo:',
            'estado' => 'Activo:',
            'nombre_completo' => 'Agente comercial:',
            'gestion_diaria' => 'Gestion comercial diaria:',
            'id_coordinador' => 'Coordinador:',
            'hacer_pedido' => 'Gestiona pedidos:',
            'hacer_recibo_caja' => 'Hacer recibo de caja:',
         ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoDepartamento()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento']);
    }
    public function getCoordinador()
    {
        return $this->hasOne(Coordinadores::className(), ['id_coordinador' => 'id_coordinador']);
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
    public function getCargo()
    {
        return $this->hasOne(Cargos::className(), ['id_cargo' => 'id_cargo']);
    }
    //subprocesos
    public function getEstadoRegistro() {
        if($this->estado == 0){
            $estadoregistro = 'SI';
        }else{
            $estadoregistro = 'NO';
        }
        return $estadoregistro;
    }
    
     public function getGestionDiaria() {
        if($this->gestion_diaria == 0){
            $gestiondiaria = 'SI';
        }else{
            $gestiondiaria = 'NO';
        }
        return $gestiondiaria;
    }
     public function getGestionPedido() {
        if($this->hacer_pedido== 0){
            $gestionapedido = 'SI';
        }else{
            $gestionapedido = 'NO';
        }
        return $gestionapedido;
    }
    public function getHacerRecibo() {
        if($this->hacer_recibo_caja== 0){
            $hacerrecibo = 'SI';
        }else{
            $hacerrecibo = 'NO';
        }
        return $hacerrecibo;
    }
}
