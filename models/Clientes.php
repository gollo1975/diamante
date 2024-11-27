<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clientes".
 *
 * @property int $id_cliente
 * @property int $id_tipo_documento
 * @property string $nit_cedula
 * @property int $dv
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $direccion
 * @property string $telefono
 * @property string $celular
 * @property string $email_cliente
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property int $tipo_regimen
 * @property int $forma_pago
 * @property int $plazo
 * @property int $autoretenedor
 * @property int $id_naturaleza
 * @property int $tipo_sociedad
 * @property string $user_name
 * @property string $fecha_creacion
 * @property string $user_name_editar
 * @property string $fecha_editado
 * @property string $observacion
 * @property int $id_posicion
 *
 * @property TipoDocumento $tipoDocumento
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 * @property NaturalezaSociedad $naturaleza
 * @property PosicionPrecio $posicion
 */
class Clientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes';
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
        $this->email_cliente = strtolower($this->email_cliente);
        $this->barrio_localidad = strtoupper($this->barrio_localidad);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'nit_cedula', 'celular', 'email_cliente', 'codigo_departamento','direccion', 'codigo_municipio', 'id_naturaleza', 'id_posicion','id_agente','id_tipo_cliente'], 'required'],
            [['id_tipo_documento', 'dv', 'tipo_regimen', 'id_forma_pago', 'plazo', 'autoretenedor', 'id_naturaleza', 'tipo_sociedad', 'id_posicion',
                'estado_cliente','cupo_asignado','id_agente','aplicar_venta_mora','presupuesto_comercial','gasto_presupuesto_comercial','id_tipo_cliente','predeterminado','id_zona'], 'integer'],
            [['fecha_creacion', 'fecha_editado'], 'safe'],
            [['observacion','barrio_localidad'], 'string'],
            ['email_cliente', 'email'],
            [['nit_cedula', 'telefono', 'celular', 'user_name', 'user_name_editar'], 'string', 'max' => 15],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'], 'string', 'max' => 15],
            [['direccion', 'email_cliente','nombre_completo','razon_social'], 'string', 'max' => 60],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_naturaleza'], 'exist', 'skipOnError' => true, 'targetClass' => NaturalezaSociedad::className(), 'targetAttribute' => ['id_naturaleza' => 'id_naturaleza']],
            [['id_posicion'], 'exist', 'skipOnError' => true, 'targetClass' => PosicionPrecio::className(), 'targetAttribute' => ['id_posicion' => 'id_posicion']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
            [['id_tipo_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCliente::className(), 'targetAttribute' => ['id_tipo_cliente' => 'id_tipo_cliente']],
            [['id_forma_pago'], 'exist', 'skipOnError' => true, 'targetClass' => FormaPago::className(), 'targetAttribute' => ['id_forma_pago' => 'id_forma_pago']],
            [['id_zona'], 'exist', 'skipOnError' => true, 'targetClass' => ZonaClientes::className(), 'targetAttribute' => ['id_zona' => 'id_zona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cliente' => 'Código:',
            'id_tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Nit/Cedula:',
            'dv' => 'Dv:',
            'id_zona' => 'Zona del cliente:',
            'primer_nombre' => 'Primer Nombre',
            'segundo_nombre' => 'Segundo Nombre',
            'primer_apellido' => 'Primer Apellido',
            'segundo_apellido' => 'Segundo Apellido',
            'razon_social' => 'Razon social:',
            'direccion' => 'Direccion:',
            'telefono' => 'Telefono:',
            'celular' => 'Celular:',
            'email_cliente' => 'Email:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'tipo_regimen' => 'Tipo regimen:',
            'id_forma_pago' => 'Forma pago:',
            'plazo' => 'Plazo:',
            'autoretenedor' => 'Autoretenedor:',
            'id_naturaleza' => 'Naturaleza:',
            'tipo_sociedad' => 'Tipo sociedad:',
            'user_name' => 'User name:',
            'fecha_creacion' => 'Fecha creacion:',
            'user_name_editar' => 'User Name Editar:',
            'fecha_editado' => 'Fecha Editado:',
            'observacion' => 'Observacion:',
            'id_posicion' => 'Posicion venta:',
            'nombre_completo' => 'Nombre del cliente:',
            'estado_cliente' => 'Activo',
            'cupo_asignado' => 'Cupo asignado:',
            'id_agente'=> 'Agente comercial:',
            'aplicar_venta_mora' => 'Venta en mora:',
            'presupuesto_comercial' => 'Presupuesto:',
            'gasto_presupuesto_comercial' => 'Gasto presupuesto:',
            'id_tipo_cliente' => 'Tipo cliente:',
            'predeterminado' => 'predeterminado',
            'barrio_localidad' => 'Barrio/localidad:',
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
    public function getFormaPago()
    {
        return $this->hasOne(FormaPago::className(), ['id_forma_pago' => 'id_forma_pago']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZona()
    {
        return $this->hasOne(ZonaClientes::className(), ['id_zona' => 'id_zona']);
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
    public function getAgenteComercial()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
     public function getPosicion()
    {
        return $this->hasOne(PosicionPrecio::className(), ['id_posicion' => 'id_posicion']);
    }
    
     public function getTipoCliente()
    {
        return $this->hasOne(TipoCliente::className(), ['id_tipo_cliente' => 'id_tipo_cliente']);
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
    public function getEstadoCliente() {
        if($this->estado_cliente == 0){
            $estadocliente = 'SI';
        }else{
            $estadocliente = 'NO';
        }
        return $estadocliente;
    }
    public function getVentaMora() {
        if($this->aplicar_venta_mora == 0){
            $ventamora = 'NO';
        }else{
            $ventamora = 'SI';
        }
        return $ventamora;
    }
    
    public function getClienteCompleto() {
        return "Cedula/Nit: {$this->nit_cedula} Nombres: {$this->nombre_completo}";
       
    }
}
