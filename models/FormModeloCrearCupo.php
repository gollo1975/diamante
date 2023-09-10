<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloCrearCupo extends Model
{
    public $nuevo_cupo;
    public $descripcion;
    public $anotacion;

    public function rules()
    {
        return [

           [['nuevo_cupo'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
           [['nuevo_cupo'], 'required', 'message' => 'Campo requerido'], 
           [['nuevo_cupo','descripcion'], 'integer'],
           ['anotacion', 'string'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'nuevo_cupo' => 'Nuevo cupo comercial',
            'descripcion' => 'Tipo cupo:',
            'anotacion' => 'Anotacion de cliente:',

        ];
    }
}
