<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matricula_empresa".
 *
 * @property int $id_empresa
 * @property int $nit_empresa
 * @property int $dv
 * @property string $razon_social
 * @property string $primer_nombre
 * @property string $segundo_ nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $razon_social_completa
 * @property string $direccion
 * @property string $telefono
 * @property string $celular
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property int $id_resolucion
 *
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 * @property Resoluciones $resolucion
 */
class MatriculaEmpresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matricula_empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'nit_empresa', 'dv', 'razon_social_completa', 'direccion', 'codigo_departamento', 'codigo_municipio'], 'required'],
            [['id_empresa', 'nit_empresa', 'dv', 'id_resolucion','documento_representante_legal','sugiere_retencion','tipo_regimen','id_naturaleza','aplica_punto_venta','calificacion_proveedor'], 'integer'],
            [['razon_social', 'razon_social_completa', 'direccion','email','representante_legal'], 'string', 'max' => 50],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'codigo_departamento', 'codigo_municipio','codigo_banco'], 'string', 'max' => 10],
            [['telefono', 'celular'], 'string', 'max' => 15],
            [['nombre_sistema', 'pagina_web'], 'string', 'max' => 30],
            [['id_empresa'], 'unique'],
            [['declaracion'], 'string', 'max' => 500],
            ['porcentaje_reteiva', 'number'],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_resolucion'], 'exist', 'skipOnError' => true, 'targetClass' => ResolucionDian::className(), 'targetAttribute' => ['id_resolucion' => 'id_resolucion']],
            [['id_resolucion'], 'exist', 'skipOnError' => true, 'targetClass' => ResolucionDian::className(), 'targetAttribute' => ['id_resolucion' => 'id_resolucion']],
            [['codigo_banco'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadBancarias::className(), 'targetAttribute' => ['codigo_banco' => 'codigo_banco']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_empresa' => 'Id',
            'nit_empresa' => 'Nit/Cedula',
            'dv' => 'Dv',
            'razon_social' => 'Razon Social',
            'primer_nombre' => 'Primer Nombre',
            'segundo_nombre' => 'Segundo Nombre',
            'primer_apellido' => 'Primer Apellido',
            'segundo_apellido' => 'Segundo Apellido',
            'razon_social_completa' => 'Razon Social Completa',
            'direccion' => 'Direccion',
            'telefono' => 'Telefono',
            'celular' => 'Celular',
            'codigo_departamento' => 'Departamento',
            'codigo_municipio' => 'Municipio',
            'id_resolucion' => 'Resolucion',
            'nombre_sistema' => 'Nombre sistema',
            'representante_legal' => 'Representante legal',
            'documento_representante_legal' => 'Documento',
            'email' => 'Email',
            'pagina_web' => 'Pagina web',
            'porcentaje_reteiva' => 'Porcentaje reteiva',
            'sugiere_retencion' => 'Sugiere retencion',
            'id_naturaleza' =>'Naturaleza',
            'tipo_regimen' => 'Tipo regimen',
            'declaracion' => 'Declaracion',
            'codigo_banco' => 'Codigo banco',
            'aplica_punto_venta' => 'aplica_punto_venta',
            'calificacion_proveedor' => '% Proveedor:',
            
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoDepartamento()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento']);
    }
     public function getNaturaleza()
    {
        return $this->hasOne(NaturalezaSociedad::className(), ['id_naturaleza' => 'id_naturaleza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipio()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio']);
    }
     public function getEntidadBancaria()
    {
        return $this->hasOne(EntidadBancarias::className(), ['codigo_banco' => 'codigo_banco']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResolucion()
    {
        return $this->hasOne(ResolucionDian::className(), ['id_resolucion' => 'id_resolucion']);
    }
    public function getTiporegimen() {
        if($this->tipo_regimen == 0){
            $tiporegimen = 'SIMPLICADO';
        }else{
            $tiporegimen = 'COMUN';
        }
        return $tiporegimen;
    }
    
   
}
