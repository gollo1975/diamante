<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transportadora".
 *
 * @property int $id_transportadora
 * @property int $tipo_documento
 * @property string $nit_cedula
 * @property int $dv
 * @property string $razon_social
 * @property string $direccion
 * @property string $email_transportadora
 * @property string $telefono
 * @property string $celular
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property string $contacto
 * @property string $celular_contacto
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property PackingPedido[] $packingPedidos
 * @property TipoDocumento $tipoDocumento
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 */
class Transportadora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transportadora';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->razon_social = strtoupper($this->razon_social); 
        $this->email_transportadora = strtolower($this->email_transportadora); 
        $this->direccion = strtoupper($this->direccion);
        $this->contacto = strtoupper($this->contacto);
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_documento', 'nit_cedula', 'dv', 'razon_social', 'direccion', 'email_transportadora', 'celular', 'codigo_departamento', 'codigo_municipio'], 'required'],
            [['tipo_documento', 'dv'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['nit_cedula', 'telefono', 'celular', 'celular_contacto', 'user_name'], 'string', 'max' => 15],
            [['razon_social', 'direccion', 'email_transportadora'], 'string', 'max' => 50],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['contacto'], 'string', 'max' => 40],
            [['tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['tipo_documento' => 'id_tipo_documento']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_transportadora' => 'Id',
            'tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Nit/Cedula:',
            'dv' => 'Dv:',
            'razon_social' => 'Razon social:',
            'direccion' => 'Direccion:',
            'email_transportadora' => 'Email:',
            'telefono' => 'Telefono:',
            'celular' => 'Celular:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'contacto' => 'Contacto:',
            'celular_contacto' => 'Celular contacto:',
            'user_name' => 'User Name:',
            'fecha_registro' => 'Fecha Registro:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackingPedidos()
    {
        return $this->hasMany(PackingPedido::className(), ['id_transportadora' => 'id_transportadora']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id_tipo_documento' => 'tipo_documento']);
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
}
