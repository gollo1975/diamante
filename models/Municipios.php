<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "municipios".
 *
 * @property string $codigo_municipio
 * @property string $municipio
 * @property string $codigo_departamento
 * @property string $codigo_interfaz
 *
 * @property MatriculaEmpresa[] $matriculaEmpresas
 * @property Departamentos $codigoDepartamento
 */
class Municipios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'municipios';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->municipio = strtoupper($this->municipio); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_municipio', 'municipio', 'codigo_departamento'], 'required'],
            [['codigo_municipio', 'codigo_departamento', 'codigo_interfaz'], 'string', 'max' => 10],
            [['municipio','usuario_creador'], 'string', 'max' => 30],
            [['codigo_municipio'], 'unique'],
            [['estado_registro','codigo_api_nomina'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_municipio' => 'Codigo',
            'municipio' => 'Municipio',
            'codigo_departamento' => 'Departamento',
            'estado_registro' => 'Activo',
            'codigo_interfaz' => 'Codigo interfaz',
            'usuario_creador' => 'User name',
            'fecha_creacion' => 'Fecha creacion',
            'codigo_api_nomina' => 'Codigo api nomina',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculaEmpresas()
    {
        return $this->hasMany(MatriculaEmpresa::className(), ['codigo_municipio' => 'codigo_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoDepartamento()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento']);
    }
    
    public function getActivo()
    {
        if($this->estado_registro == 0){
            $activor = "SI";
        }else{
            $activor = "NO";
        }
        return $activor;
    }
}
