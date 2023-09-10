<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormCrearPrecioVenta extends Model
{
    public $precio_uno;  
    public $precio_dos;  
    public $precio_tres;  

    public function rules()
    {
        return [

           [['precio_uno','precio_dos','precio_tres'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],  
           [['precio_uno','precio_dos','precio_tres'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'precio_uno' => 'Precio venta uno:', 
            'precio_dos' => 'Precio venta dos:',
            'precio_tres' => 'Precio venta tres:',

        ];
    }
}