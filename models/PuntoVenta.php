<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "punto_venta".
 *
 * @property int $id_punto
 * @property string $nombre_punto
 * @property int $direccion_punto
 * @property string $telefono
 * @property string $celular
 * @property string $email
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property string $fecha_inicio
 * @property string $user_name
 * @property int $administrador
 * @property string $fecha_registro
 *
 * @property Departamentos $codigoDepartamento
 * @property Municipios $codigoMunicipio
 */
class PuntoVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'punto_venta';
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_punto = strtoupper($this->nombre_punto); 
        $this->direccion_punto = strtoupper($this->direccion_punto); 
        $this->administrador = strtoupper($this->administrador); 
        $this->email = strtolower($this->email); 
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_punto', 'fecha_inicio', 'administrador'], 'required'],
            [['predeterminado'], 'integer'],
            [['fecha_inicio', 'fecha_registro'], 'safe'],
            [['nombre_punto','direccion_punto','administrador'], 'string', 'max' => 50],
            [['telefono', 'celular', 'user_name'], 'string', 'max' => 15],
            [['email'], 'email'],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
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
            'id_punto' => 'Codigo:',
            'nombre_punto' => 'Nombre Punto:',
            'direccion_punto' => 'Direccion:',
            'telefono' => 'Telefono:',
            'celular' => 'Celular:',
            'email' => 'Email:',
            'codigo_departamento' => 'Departamento:',
            'codigo_municipio' => 'Municipio:',
            'fecha_inicio' => 'Fecha inicio',
            'user_name' => 'User name',
            'administrador' => 'Administrador:',
            'fecha_registro' => 'Fecha Registro:',
            'predeterminado' => 'Predeterminado:',
        ];
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
    
    public function getPredeterminadoPunto() {
        if($this->predeterminado == 0){
            $predeterminadopunto = 'NO';
        }else{
            $predeterminadopunto = 'SI';
        }
        return $predeterminadopunto;
    }            
}
