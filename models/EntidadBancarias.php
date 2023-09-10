<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad_bancarias".
 *
 * @property string $codigo_banco
 * @property int $nit_banco
 * @property int $dv
 * @property string $entidad_bancaria
 * @property string $direccion_banco
 * @property string $telefono_banco
 * @property int $tipo_producto
 * @property string $producto
 * @property int $id_empresa
 * @property int $convenio_nomina
 * @property int $convenio_proveedor
 * @property int $convenio_empresa
 * @property int $estado_registro
 * @property string $user_name
 * @property string $codigo_interfaz
 *
 * @property MatriculaEmpresa $empresa
 * @property Proveedor[] $proveedors
 */
class EntidadBancarias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad_bancarias';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->entidad_bancaria = strtoupper($this->entidad_bancaria);
        $this->direccion_banco = strtoupper($this->direccion_banco);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_banco', 'dv', 'entidad_bancaria', 'producto', 'codigo_municipio', 'codigo_departamento','id_tipo_documento','tipo_producto'], 'required'],
            [['nit_cedula', 'dv', 'nit_empresa', 'convenio_nomina', 'convenio_proveedor', 'convenio_empresa', 'estado_registro','validador_digitos','id_tipo_documento'], 'integer'],
            [['codigo_banco','codigo_departamento','codigo_municipio'], 'string', 'max' => 10],
            [['entidad_bancaria','tipo_producto'], 'string'],
            [['direccion_banco'], 'string', 'max' => 50],
            [['telefono_banco', 'user_name'], 'string', 'max' => 15],
            [['producto'], 'string', 'max' => 12],
            [['codigo_interfaz'], 'string', 'max' => 4],
            [['codigo_banco'], 'unique'],
            [['fecha_creacion'],'safe'],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_banco' => 'Codigo Banco:',
            'nit_cedula' => 'Nit:',
            'dv' => 'Dv:',
            'entidad_bancaria' => 'Entidad Bancaria:',
            'direccion_banco' => 'Direccion:',
            'telefono_banco' => 'Telefono:',
            'tipo_producto' => 'Tipo Producto:',
            'producto' => 'Producto:',
            'nit_empresa' => 'Nit Empresa',
            'convenio_nomina' => 'Convenio Nomina',
            'convenio_proveedor' => 'Convenio Proveedor',
            'convenio_empresa' => 'Convenio Empresa',
            'estado_registro' => 'Estado Registro',
            'user_name' => 'User Name',
            'codigo_interfaz' => 'Codigo Interfaz:',
            'validador_digitos' => 'Digitos:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'id_tipo_documento' => 'Tipo documento:',
            'fecha_creacion' => 'Fecha registro:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
       public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
    

      public function getDepartamento()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento']);
    }
    
      public function getMunicipio()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio']);
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasMany(Proveedor::className(), ['codigo_banco' => 'codigo_banco']);
    }
    
    
      public function getTipoCuenta() {
        if($this->tipo_producto == 'S'){
            $tipocuenta = 'AHORRO';
        }else{
            $tipocuenta = 'CORRIENTE';
        }
        return $tipocuenta;
    }
    public function getEstadoRegistro() {
        if($this->estado_registro == '0'){
            $estadoregistro = 'SI';
        }else{
            $estadoregistro = 'NO';
        }
        return $estadoregistro;
    }
    
    public function getConvenioNomina() {
        if($this->convenio_nomina == '0'){
            $convenionomina = 'NO';
        }else{
            $convenionomina = 'SI';
        }
        return $convenionomina;
    }
    public function getConvenioProveedor() {
        if($this->convenio_proveedor == '0'){
            $convenioproveedor = 'NO';
        }else{
            $convenioproveedor = 'SI';
        }
        return $convenioproveedor;
    }
    public function getConvenioEmpresa() {
        if($this->convenio_empresa == '0'){
            $convenioempresa = 'NO';
        }else{
            $convenioempresa = 'SI';
        }
        return $convenioempresa;
    }
}
