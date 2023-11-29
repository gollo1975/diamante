<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proveedor".
 *
 * @property int $id_provedor
 * @property int $id_tipo_documento
 * @property string $nit/cedula
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $razon_social
 * @property string $nombre_completo
 * @property string $direccion
 * @property string $email
 * @property string $telefono
 * @property string $celular
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property string $nombre_contacto
 * @property string $celular_contacto
 * @property int $tipo_regimen
 * @property int $forma_pago
 * @property int $plazo
 * @property int $autoretenedor
 * @property int $id_naturaleza
 * @property int $tipo_sociedad
 * @property string $codigo_banco
 * @property int $tipo_cuenta
 * @property string $producto
 * @property int $tipo_transacion
 * @property string $user_name
 * @property string $fecha_creacion
 * @property int $id_empresa
 *
 * @property TipoDocumento $tipoDocumento
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 * @property NaturalezaSociedad $naturaleza
 * @property EntidadBancarias $codigoBanco
 * @property MatriculaEmpresa $empresa
 */
class Proveedor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proveedor';
    }
      public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->razon_social = strtoupper($this->razon_social);
        $this->primer_nombre = strtoupper($this->primer_nombre);
        $this->segundo_nombre = strtoupper($this->segundo_nombre);
        $this->primer_apellido = strtoupper($this->primer_apellido);
        $this->segundo_apellido = strtoupper($this->segundo_apellido);
        $this->direccion = strtoupper($this->direccion);
        $this->email = strtolower($this->email);
        $this->nombre_contacto = strtoupper($this->nombre_contacto);
 
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'nit_cedula', 'direccion', 'email', 'telefono', 'codigo_departamento', 'codigo_municipio', 'id_naturaleza','dv','forma_pago','autoretenedor','tipo_regimen'], 'required'],
            [['id_tipo_documento', 'tipo_regimen', 'forma_pago', 'plazo', 'autoretenedor', 'id_naturaleza', 'tipo_sociedad', 'tipo_transacion', 'id_empresa','dv','predeterminado'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['nit_cedula', 'telefono', 'celular', 'celular_contacto', 'producto', 'user_name'], 'string', 'max' => 15],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'], 'string', 'max' => 12],
            [['razon_social', 'nombre_completo', 'nombre_contacto'], 'string', 'max' => 50],
            [['direccion', 'email'], 'string', 'max' => 60],
            [['observacion'], 'string', 'max' => 100],
            [['tipo_cuenta'], 'string'],
            [['codigo_departamento', 'codigo_municipio', 'codigo_banco'], 'string', 'max' => 10],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_naturaleza'], 'exist', 'skipOnError' => true, 'targetClass' => NaturalezaSociedad::className(), 'targetAttribute' => ['id_naturaleza' => 'id_naturaleza']],
            [['codigo_banco'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadBancarias::className(), 'targetAttribute' => ['codigo_banco' => 'codigo_banco']],
            [['id_empresa'], 'exist', 'skipOnError' => true, 'targetClass' => MatriculaEmpresa::className(), 'targetAttribute' => ['id_empresa' => 'id_empresa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_proveedor' => 'Id:',
            'id_tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Documento proveedor:',
            'dv' => 'Dv:',
            'primer_nombre' => 'Primer Nombre:',
            'segundo_nombre' => 'Segundo Nombre:',
            'primer_apellido' => 'Primer Apellido:',
            'segundo_apellido' => 'Segundo Apellido:',
            'razon_social' => 'Razon Social:',
            'nombre_completo' => 'Nombre Completo:',
            'direccion' => 'Dirección:',
            'email' => 'Email:',
            'telefono' => 'Telefono:',
            'celular' => 'Celular:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'nombre_contacto' => 'Nombre Contacto:',
            'celular_contacto' => 'Celular Contacto:',
            'tipo_regimen' => 'Tipo Regimen:',
            'forma_pago' => 'Forma Pago:',
            'plazo' => 'Plazo',
            'autoretenedor' => 'Autoretenedor:',
            'id_naturaleza' => 'Naturaleza:',
            'tipo_sociedad' => 'Tipo Sociedad:',
            'codigo_banco' => 'Banco:',
            'tipo_cuenta' => 'Tipo Cuenta:',
            'producto' => 'Producto:',
            'tipo_transacion' => 'Tipo Transacion:',
            'user_name' => 'User Name',
            'fecha_creacion' => 'Fecha Creacion',
            'id_empresa' => 'Empresa',
            'observacion' => 'Observacion:',
            'predeterminado' => 'Predeterminado:',
            
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
    public function getNaturaleza()
    {
        return $this->hasOne(NaturalezaSociedad::className(), ['id_naturaleza' => 'id_naturaleza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoBanco()
    {
        return $this->hasOne(EntidadBancarias::className(), ['codigo_banco' => 'codigo_banco']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   public function getEmpresa()
    {
        return $this->hasOne(MatriculaEmpresa::className(), ['id_empresa' => 'id_empresa']);
    }
    
    public function getFormaPago() {
        if($this->forma_pago == 1){
            $formapago = 'CONTADO';
        }else{
            $formapago = 'CREDITO';
        }
        return $formapago;
    }
    
     public function getTipoRegimen() {
        if($this->tipo_regimen == 0){
            $tiporegimen = 'SIMPLIFICADO';
        }else{
            $tiporegimen = 'COMUN';
        }
        return $tiporegimen;
    }
    
    public function getAutoretenedorVenta() {
        if($this->autoretenedor == 0){
            $autoretenedor = 'NO';
        }else{
            $autoretenedor = 'SI';
        }
        return $autoretenedor;
    }
    
    public function getTipoSociedad() {
        if($this->tipo_sociedad == 0){
            $tiposociedad = 'NATURAL';
        }else{
            $tiposociedad = 'JURIDICA';
        }
        return $tiposociedad;
    }
     public function getTipoCuenta() {
        if($this->tipo_cuenta == 'S'){
            $tipocuenta = 'AHORRO';
        }else{
            $tipocuenta = 'CORRIENTE';
        }
        return $tipocuenta;
    }
    
     public function getTipoTransacion() {
        if($this->tipo_transacion == '27'){
            $tipotransacion = 'ABONO A CTA CORRIENTE';
        }else{
            $tipotransacion = 'ABONO A CTA AHORRO';
        }
        return $tipotransacion;
    }
    public function getProveedorPredeterminado() {
        if($this->predeterminado == 0){
           $proveedorpredeterminado = 'NO';
        }else{
            $proveedorpredeterminado = 'SI';
        }
        return $proveedorpredeterminado;
    }
}
