<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "caja_compensacion".
 *
 * @property int $id_caja
 * @property string $caja
 * @property string $telefono
 * @property string $direccion
 * @property string $codigo
 * @property string $codigo_municipio
 * @property int $estado
 * @property double $porcentaje
 * @property string $user_name
 * @property string $fecha_hora_registro
 *
 * @property Municipios $codigoMunicipio
 */
class CajaCompensacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'caja_compensacion';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->caja = strtoupper($this->caja); 
        $this->codigo = strtoupper($this->codigo); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caja', 'codigo_municipio', 'porcentaje', 'user_name'], 'required'],
            [['estado'], 'integer'],
            [['porcentaje'], 'number'],
            [['fecha_hora_registro'], 'safe'],
            [['caja'], 'string', 'max' => 40],
            [['telefono', 'user_name'], 'string', 'max' => 15],
            [['direccion'], 'string', 'max' => 50],
            [['codigo', 'codigo_municipio'], 'string', 'max' => 10],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_caja' => 'Id',
            'caja' => 'Caja de compensación:',
            'telefono' => 'Telefono:',
            'direccion' => 'Direccion:',
            'codigo' => 'Codigo interfaz:',
            'codigo_municipio' => 'Municipio:',
            'estado' => 'Activo',
            'porcentaje' => 'Porcentaje:',
            'user_name' => 'User Name',
            'fecha_hora_registro' => 'Fecha Hora Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipio()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio']);
    }
    
    public function getActivo() {
        if($this->estado == 0){
            $activo = 'SI';
        }else{
            $activo = 'NO';
        }
        return $activo;
    }
}
