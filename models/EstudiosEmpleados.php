<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estudios_empleados".
 *
 * @property int $id
 * @property string $codigo_municipio
 * @property int $documento
 * @property int $id_empleado
 * @property int $id_tipo_estudio
 * @property string $institucion_educativa
 * @property string $titulo_obtenido
 * @property int $anio_cursado
 * @property string $fecha_inicio
 * @property string $fecha_terminacion
 * @property int $graduado
 * @property string $fecha_vencimiento
 * @property string $registro
 * @property int $validar_vencimiento
 * @property string $observacion
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property Municipios $codigoMunicipio
 * @property Empleados $empleado
 * @property TipoEstudio $tipoEstudio
 */
class EstudiosEmpleados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudios_empleados';
    }
    
      public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->institucion_educativa = strtoupper($this->institucion_educativa); 
        $this->titulo_obtenido = strtoupper($this->titulo_obtenido); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_municipio', 'id_empleado', 'id_profesion', 'fecha_inicio', 'fecha_terminacion'], 'required'],
            [['documento', 'id_empleado', 'id_profesion', 'anio_cursado', 'graduado', 'validar_vencimiento'], 'integer'],
            [['fecha_inicio', 'fecha_terminacion', 'fecha_vencimiento', 'fecha_registro'], 'safe'],
            [['codigo_municipio'], 'string', 'max' => 10],
            [['institucion_educativa', 'titulo_obtenido'], 'string', 'max' => 50],
            [['registro'], 'string', 'max' => 20],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_profesion'], 'exist', 'skipOnError' => true, 'targetClass' => Profesiones::className(), 'targetAttribute' => ['id_profesion' => 'id_profesion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo_municipio' => 'Municipio:',
            'documento' => 'Documento:',
            'id_empleado' => 'Empleado:',
            'id_profesion' => 'Tipo estudio:',
            'institucion_educativa' => 'Institucion educativa:',
            'titulo_obtenido' => 'Titulo obtenido:',
            'anio_cursado' => 'Año cursado:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_terminacion' => 'Fecha terminacion:',
            'graduado' => 'Graduado:',
            'fecha_vencimiento' => 'Fecha vencimiento:',
            'registro' => 'Registro:',
            'validar_vencimiento' => 'Validar vencimiento:',
            'observacion' => 'Observacion:',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
        ];
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
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesion()
    {
        return $this->hasOne(Profesiones::className(), ['id_profesion' => 'id_profesion']);
    }
    
    public function getGraduadoEstudio() {
        if($this->graduado == 0){
            $graduadoestudio = 'NO';
        }else{
            $graduadoestudio = 'SI';
        }
        return $graduadoestudio;
            
    }
    
    public function getValidarEstudio() {
        if($this->validar_vencimiento == 0){
            $validarestudio = 'NO';
        }else{
            $validarestudio = 'SI';
        }
        return $validarestudio;
            
    }
}
