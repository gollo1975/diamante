<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class ModeloGestionComercial extends Model
{
    public $cumplida;
    public $observacion;
    public $tipo_visita;


    public function rules()
    {
        return [

           [['observacion'], 'required', 'message' => 'Campo requerido'], 
           [['cumplida','tipo_visita'], 'integer'],
            [['observacion'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [

            'cumplida' => 'Cumplida:',
            'observacion' => 'Nota',
            'tipo_visita' => 'Tipo visita:',
            

        ];
    }
}
