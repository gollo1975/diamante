<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class ModelAprobarProveedor extends Model
{
    public $aprobado;
    public $nota;
    public $cerrar;


    public function rules()
    {
        return [

           [['aprobado','nota','cerrar'], 'required', 'message' => 'Campo requerido'], 
           [['aprobado','cerrar'], 'integer'],
            [['nota'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'aprobado' => 'Aprobar:',
            'nota' => 'Nota:',
            'cerrar' => 'Cerrar:',

        ];
    }
}
