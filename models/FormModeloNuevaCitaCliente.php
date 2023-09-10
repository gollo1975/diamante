<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloNuevaCitaCliente extends Model
{
    public $cliente;
    public $tipo_visita;
    public $hora_visita;
    public $nota;

    public function rules()
    {
        return [

           [['cliente','tipo_visita', 'hora_visita'], 'required', 'message' => 'Campo requerido'], 
           [['cliente','tipo_visita'], 'integer'],
            [['hora_visita','nota'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cliente' => 'Cliente:',
            'tipo_visita' => 'Tipo visita:',
            'hora_visita' => 'Hora visita:',
            'nota' => 'Nota',
            

        ];
    }
}
