<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * ContactForm is the model behind the contact form.
 */
class FormConfiguracionSalario extends Model
{
    public $id_salario;
    public $salario_minimo_actual;
    public $auxilio_transporte_actual;
    public $anio;
    public $estado;
    public $fecha_cierre;
    public $fecha_aplicacion;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
         return [
            [['salario_minimo_actual', 'auxilio_transporte_actual', 'anio', 'estado'], 'integer'],
            [['salario_minimo_actual','auxilio_transporte_actual','anio','fecha_cierre','fecha_aplicacion'], 'required', 'message' => 'Este campo no puede ser vacio'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_salario' => 'Id',
            'salario_minimo_actual' => 'Salario minino actual',
            'auxilio_transporte_actual' => 'Auxilio transporte actual',
            'anio' => 'Año',
            'estado' => 'Activo:',
            'fecha_cierre' =>'Fecha cierre:',
            'fecha_aplicacion' => 'Fecha aplicacion:',
            
        ];
    }
}
