<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloNuevaCitaProspecto extends Model
{
    public $tipo_visita;
    public $hora_visita;
    public $nota;
    public $fecha_cita;

    public function rules()
    {
        return [

           [['tipo_visita'], 'integer'],
           [['hora_visita','nota'], 'string'],
           ['fecha_cita', 'safe'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'fecha_cita' => 'Fecha visita:',
            'tipo_visita' => 'Tipo visita:',
            'hora_visita' => 'Hora visita:',
            'nota' => 'Nota',
            

        ];
    }
}
