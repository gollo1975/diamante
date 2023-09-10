<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "departamentos".
 *
 * @property string $codigo_departamento
 * @property string $departamento
 * @property string $codigo_pais
 * @property string $codigo_interfaz
 *
 * @property Pais $codigoPais
 * @property MatriculaEmpresa[] $matriculaEmpresas
 * @property Municipios[] $municipios
 */
class Departamentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departamentos';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->departamento = strtoupper($this->departamento); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_departamento', 'departamento', 'codigo_pais'], 'required'],
            [['estado_registro'], 'integer'],
            [['codigo_departamento', 'codigo_pais', 'codigo_interfaz'], 'string', 'max' => 10],
            [['departamento'], 'string', 'max' => 30],
            [['usuario_creador'], 'string', 'max' => 15],
            [['fecha_creacion'], 'safe'],
            [['codigo_departamento'], 'unique'],
            [['codigo_pais'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['codigo_pais' => 'codigo_pais']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_departamento' => 'Codigo',
            'departamento' => 'Departamento',
            'codigo_pais' => 'Pais',
            'usuario_creador' => 'User name',
            'codigo_interfaz' => 'Codigo Interfaz',
            'estado_registro' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoPais()
    {
        return $this->hasOne(Pais::className(), ['codigo_pais' => 'codigo_pais']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculaEmpresas()
    {
        return $this->hasMany(MatriculaEmpresa::className(), ['codigo_departamento' => 'codigo_departamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMunicipios()
    {
        return $this->hasMany(Municipios::className(), ['codigo_municipios' => 'codigo_municipios']);
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
