<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_salario".
 *
 * @property int $id_salario
 * @property int $salario_minimo_actual
 * @property int $salario_minimo_anterior
 * @property int $auxilio_transporte_actual
 * @property int $auxilio_transporte_anterior
 * @property int $salario_incapacidad
 * @property double $porcentaje_incremento
 * @property int $anio
 * @property int $estado
 * @property string $user_name
 * @property string $fecha_aplicacion
 * @property string $fecha_cierre
 * @property string $fecha_creacion
 */
class ConfiguracionSalario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_salario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['salario_minimo_actual', 'salario_minimo_anterior', 'auxilio_transporte_actual', 'auxilio_transporte_anterior', 'salario_incapacidad', 'anio', 'estado'], 'integer'],
            [['porcentaje_incremento'], 'number'],
            [['fecha_aplicacion', 'fecha_cierre'], 'required'],
            [['fecha_aplicacion', 'fecha_cierre', 'fecha_creacion'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_salario' => 'Id Salario',
            'salario_minimo_actual' => 'Salario Minimo Actual',
            'salario_minimo_anterior' => 'Salario Minimo Anterior',
            'auxilio_transporte_actual' => 'Auxilio Transporte Actual',
            'auxilio_transporte_anterior' => 'Auxilio Transporte Anterior',
            'salario_incapacidad' => 'Salario Incapacidad',
            'porcentaje_incremento' => 'Porcentaje Incremento',
            'anio' => 'Año',
            'estado' => 'Activo',
            'user_name' => 'User Name',
            'fecha_aplicacion' => 'Fecha Aplicacion',
            'fecha_cierre' => 'Fecha Cierre',
            'fecha_creacion' => 'Fecha Creacion',
        ];
    }
    public function getActivo()
    {
        if ($this->estado == 1){
            $estado = 'SI';
    }else {
           $estado = 'NO'; 
        } 
        return $estado; 
    }
}
