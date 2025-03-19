<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empleados".
 *
 * @property int $id_empleado
 * @property int $tipo_empleado
 * @property int $id_tipo_documento
 * @property int $identificacion
 * @property int $dv
 * @property string $nombre1
 * @property string $nombre2
 * @property string $apellido1
 * @property string $apellido2
 * @property string $direccion
 * @property string $telefono
 * @property string $celular
 * @property string $email_empleado
 * @property string $codigo_departamento_residencia
 * @property string $codigo_municipio_residencia
 * @property string $barrio
 * @property int $estado_civil
 * @property string $fecha_expedicion_documento
 * @property string $codigo_municipio_expedicion
 * @property int $id_grupo
 * @property int $genero
 * @property string $fecha_nacimiento
 * @property string $codigo_municipio_nacimiento
 * @property int $padre_familia
 * @property int $cabeza_hogar
 * @property int $discapacitado
 * @property int $id_banco
 * @property string $tipo_cuenta
 * @property string $numero_cuenta
 * @property int $tipo_transacion
 * @property int $id_profesion
 * @property string $fecha_ingreso
 * @property string $fecha_retiro
 * @property string $fecha_hora_registro
 * @property string $user_name
 * @property string $user_name_editado
 * @property string $fecha_hora_editado
 * @property string $observacion
 * @property string $talla_zapato
 * @property string $talla_pantalon
 * @property string $talla_camisa
 *
 * @property TipoEmpleado $tipoEmpleado
 * @property Municipios $codigoMunicipioExpedicion
 * @property TipoDocumento $tipoDocumento
 * @property Departamentos $codigoDepartamentoResidencia
 * @property Municipios $codigoMunicipioResidencia
 * @property GrupoSanguineo $grupo
 * @property Municipios $codigoMunicipioNacimiento
 * @property BancoEmpleado $banco
 * @property Profesiones $profesion
 * @property Transaciones $tipoTransacion
 */
class Empleados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empleados';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre1 = strtoupper($this->nombre1);
        $this->nombre2 = strtoupper($this->nombre2);
        $this->apellido1 = strtoupper($this->apellido1);
        $this->apellido2 = strtoupper($this->apellido2);
        $this->email_empleado = strtolower($this->email_empleado);
        $this->direccion = strtoupper($this->direccion);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_empleado', 'id_tipo_documento', 'dv', 'nombre1', 'apellido1', 'direccion', 'celular', 'email_empleado', 'codigo_departamento_residencia', 'codigo_municipio_residencia',
                'barrio', 'estado_civil', 'fecha_expedicion_documento', 'codigo_municipio_expedicion', 'id_grupo', 'genero', 'fecha_nacimiento', 'codigo_municipio_nacimiento', 'padre_familia', 'cabeza_hogar',
                'discapacitado', 'id_banco', 'tipo_cuenta', 'numero_cuenta', 'tipo_transacion', 'id_profesion'], 'required'],
            ['nit_cedula', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['tipo_empleado', 'id_tipo_documento', 'nit_cedula', 'dv', 'estado_civil', 'id_grupo', 'genero', 'padre_familia', 'cabeza_hogar', 'discapacitado', 'id_banco', 'tipo_transacion', 'id_profesion','estado','id_forma_pago'], 'integer'],
            [['fecha_expedicion_documento', 'fecha_nacimiento', 'fecha_ingreso', 'fecha_retiro', 'fecha_hora_registro', 'fecha_hora_editado'], 'safe'],
            [['nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefono', 'celular', 'numero_cuenta', 'user_name', 'user_name_editado'], 'string', 'max' => 15],
            [['direccion', 'email_empleado'], 'string', 'max' => 50],
            [['codigo_departamento_residencia', 'codigo_municipio_residencia', 'codigo_municipio_expedicion', 'codigo_municipio_nacimiento', 'talla_zapato', 'talla_pantalon', 'talla_camisa'], 'string', 'max' => 10],
            [['barrio'], 'string', 'max' => 20],
            [['tipo_cuenta'], 'string', 'max' => 1],
            [['observacion'], 'string', 'max' => 100],
            [['nombre_completo'],'string'],
            ['email_empleado', 'email'],
            [['tipo_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEmpleado::className(), 'targetAttribute' => ['tipo_empleado' => 'tipo_empleado']],
            [['codigo_municipio_expedicion'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio_expedicion' => 'codigo_municipio']],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
            [['codigo_departamento_residencia'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento_residencia' => 'codigo_departamento']],
            [['codigo_municipio_residencia'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio_residencia' => 'codigo_municipio']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoSanguineo::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['codigo_municipio_nacimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio_nacimiento' => 'codigo_municipio']],
            [['id_banco'], 'exist', 'skipOnError' => true, 'targetClass' => BancoEmpleado::className(), 'targetAttribute' => ['id_banco' => 'id_banco']],
            [['id_profesion'], 'exist', 'skipOnError' => true, 'targetClass' => Profesiones::className(), 'targetAttribute' => ['id_profesion' => 'id_profesion']],
            [['tipo_transacion'], 'exist', 'skipOnError' => true, 'targetClass' => Transaciones::className(), 'targetAttribute' => ['tipo_transacion' => 'tipo_transacion']],
            [['id_forma_pago'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['id_forma_pago' => 'id_forma_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_empleado' => 'Id:',
            'tipo_empleado' => 'Area:',
            'id_tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Documento',
            'dv' => 'Dv',
            'nombre1' => 'Primer nombre:',
            'nombre2' => 'Segundo nombre:',
            'apellido1' => 'Primer apellido:',
            'apellido2' => 'Segundo apellido:',
            'nombre_completo' => 'Nombre completo:',
            'direccion' => 'Direccion:',
            'telefono' => 'Telefono:',
            'celular' => 'Celular:',
            'email_empleado' => 'Email:',
            'codigo_departamento_residencia' => 'Departamento residencia:',
            'codigo_municipio_residencia' => 'Municipio residencia:',
            'barrio' => 'Barrio:',
            'estado_civil' => 'Estado civil:',
            'fecha_expedicion_documento' => 'Fecha expedicion:',
            'codigo_municipio_expedicion' => 'Municipio expedicion:',
            'id_grupo' => 'Grupo sanguineo:',
            'genero' => 'Genero:',
            'fecha_nacimiento' => 'Fecha nacimiento:',
            'codigo_municipio_nacimiento' => 'Municipio nacimiento:',
            'padre_familia' => 'Padre familia:',
            'cabeza_hogar' => 'Cabeza hogar:',
            'discapacitado' => 'Discapacitado:',
            'id_banco' => 'Banco:',
            'tipo_cuenta' => 'Tipo cuenta:',
            'numero_cuenta' => 'Numero cuenta:',
            'tipo_transacion' => 'Tipo transacion:',
            'id_profesion' => 'Nivel de estudio:',
            'fecha_ingreso' => 'Fecha Ingreso',
            'fecha_retiro' => 'Fecha Retiro',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'user_name' => 'User Name',
            'user_name_editado' => 'User Name Editado',
            'fecha_hora_editado' => 'Fecha Hora Editado',
            'observacion' => 'Observacion:',
            'talla_zapato' => 'Talla zapato:',
            'talla_pantalon' => 'Talla pantalon:',
            'talla_camisa' => 'Talla camisa:',
            'estado' => 'Activo:',
            'id_forma_pago' => 'Forma de pago:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoEmpleado()
    {
        return $this->hasOne(TipoEmpleado::className(), ['tipo_empleado' => 'tipo_empleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipioExpedicion()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio_expedicion']);
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
    public function getCodigoDepartamentoResidencia()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento_residencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipioResidencia()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio_residencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoSanguineo::className(), ['id_grupo' => 'id_grupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipioNacimiento()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio_nacimiento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanco()
    {
        return $this->hasOne(BancoEmpleado::className(), ['id_banco' => 'id_banco']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesion()
    {
        return $this->hasOne(Profesiones::className(), ['id_profesion' => 'id_profesion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoTransacion()
    {
        return $this->hasOne(Transaciones::className(), ['tipo_transacion' => 'tipo_transacion']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormaPago()
    {
        return $this->hasOne(FormaPago::className(), ['id_forma_pago' => 'id_forma_pago']);
    }
    
    //PROCESOS
    public function getGeneroEmpleado() {
        if($this->genero == 1){
            $generoempleado = 'MASCULINO';
        }else{
            $generoempleado = 'FEMENINO';
        }
        return $generoempleado;
    }
    
     public function getEstadoCivil() {
        if($this->estado_civil == 1){
            $estadocivil = 'SOLTERO';
        }else{
            if($this->estado_civil == 2){
                $estadocivil = 'UNION LIBRE';
            }else{
                if($this->estado_civil == 3){
                      $estadocivil = 'CASADO';
                }else{
                     if($this->estado_civil == 4){
                         $estadocivil = 'VIUDO';
                     }else{
                         $estadocivil = 'DIVORCIADO';
                     }
                }
            }
        }
        return $estadocivil;
    }
    
    public function getPadreFamilia() {
        if($this->padre_familia == 1){
            $padrefamilia = 'SI';
        }else{
            $padrefamilia = 'NO';
        }
        return $padrefamilia;
    }
    
     public function getCabezaHogar() {
        if($this->cabeza_hogar == 1){
            $cabezahogar = 'SI';
        }else{
            $cabezahogar = 'NO';
        }
        return $cabezahogar;
    }
    
     public function getDiscapacidadEmpleado() {
        if($this->discapacitado == 1){
            $discapacidadempleado = 'SI';
        }else{
            $discapacidadempleado = 'NO';
        }
        return $discapacidadempleado;
    }
    
    public function getTipoCuenta() {
        if($this->tipo_cuenta == 'S'){
            $tipocuenta = 'AHORRO';
        }else{
            $tipocuenta = 'CORRIENTE';
        }
        return $tipocuenta;
    }
    
    public function getEstadoActivo() {
        if($this->estado == 0){
            $estadoactivo = 'SI';
        }else{
            $estadoactivo = 'NO';
        }
        return $estadoactivo;
    }
    
    
}
