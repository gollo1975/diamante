<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente_prospecto".
 *
 * @property int $id_prospecto
 * @property int $id_tipo_documento
 * @property string $nit_cedula
 * @property int $dv
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $razon_social
 * @property string $celular
 * @property string $email_prospecto
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property int $id_agente
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property TipoDocumento $tipoDocumento
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 * @property AgentesComerciales $agente
 */
class ClienteProspecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente_prospecto';
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
        $this->direccion_prospecto = strtoupper($this->direccion_prospecto);
        $this->email_prospecto = strtolower($this->email_prospecto);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'nit_cedula', 'celular', 'direccion_prospecto', 'email_prospecto', 'codigo_departamento', 'codigo_municipio'], 'required'],
            [['id_tipo_documento', 'dv', 'id_agente'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['nit_cedula', 'celular', 'user_name'], 'string', 'max' => 15],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'], 'string', 'max' => 12],
            [['razon_social', 'email_prospecto','direccion_prospecto'], 'string', 'max' => 50],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['email_prospecto'], 'email'],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prospecto' => 'Código',
            'id_tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Documento:',
            'dv' => 'Dv:',
            'primer_nombre' => 'Primer nombre:',
            'segundo_nombre' => 'Segundo nombre:',
            'primer_apellido' => 'Primer apellido:',
            'segundo_apellido' => 'Segundo apellido:',
            'razon_social' => 'Razon social:',
            'celular' => 'Celular:',
            'email_prospecto' => 'Email:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'id_agente' => 'Agente:',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'direccion_prospecto' => 'Direccion:'
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
    public function getAgente()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
}
