<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloContactoCliente extends Model
{
    public $nombres;
    public $apellidos;
    public $celular;
    public $email;
    public $cargo;
    public $fecha_nacimiento;

    public function rules()
    {
        return [

           [['cargo'], 'required', 'message' => 'Campo requerido'], 
           [['cargo'], 'integer'],
           [['nombres','apellidos','celular'], 'string'],
           ['email', 'email'],
           ['fecha_nacimiento', 'safe'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'cargo' => 'Cargo:',
            'nombres' => 'Nombres:',
            'apellidos' => 'Apellidos:',
            'celular' => 'Celular:',
            'email' => 'Email:',
            'fecha_nacimiento' => 'Fecha nacimiento:',
            

        ];
    }
}
