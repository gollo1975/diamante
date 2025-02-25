<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroConsultaAdicionPermanente extends Model
{
    public $id_grupo_pago;
    public $id_empleado;
    public $codigo_salario;
    public $tipo_adicion;
    public $permanente;


    public function rules()
    {
        return [

            [['id_grupo_pago', 'id_empleado','codigo_salario','tipo_adicion'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
           
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_grupo_pago' => 'Grupo:',
            'id_empleado' => 'Empleado:',
            'codigo_salario' =>'Concepto:', 
            'tipo_adicion' => 'Tipo adicion:',
            'permanente' =>'permanente',
           
        ];
    }
}