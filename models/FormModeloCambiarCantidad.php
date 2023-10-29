<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormModeloCambiarCantidad extends Model
{
    public $cantidades;  
    public $fecha;
    public $nuevo_precio;
    public $cliente;
    public $pedido_virtual;
    public function rules()
    {
        return [

           [['cantidades','nuevo_precio','cliente'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
           ['fecha', 'safe'], 
           ['pedido_virtual', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cantidades' => 'N. Cantidad:', 
            'fecha' => 'F. Vencimiento:',
            'nuevo_precio' => 'Nuevo precio:',
            'cliente' => 'Cliente:',
            'pedido_virtual' => 'Pedido virtual:',

        ];
    }
}
