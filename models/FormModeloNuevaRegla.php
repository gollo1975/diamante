<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloNuevaRegla extends Model
{
    public $limite_venta;
    public $limite_presupuesto;
    public $fecha_cierre;

    public function rules()
    {
        return [

           [['limite_venta','limite_presupuesto'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
           [['fecha_cierre'], 'required', 'message' => 'Campo requerido'], 
           [['fecha_cierre'], 'safe'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'limite_venta' => 'Limite venta:',
            'limite_presupuesto' => 'Limite presupuesto:',
            'fecha_cierre' => 'Fecha cierre:',

        ];
    }
}
