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
    public $tipo_precio;
    public $nuevo_precio;
    public $cliente;
    public $pedido_virtual;
    public $descuento;
    public function rules()
    {
        return [

           [['cantidades','nuevo_precio','cliente', 'tipo_precio', 'pedido_virtual','descuento'], 'integer'],
           ['fecha', 'safe'], 
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
            'tipo_precio' => 'Tipo precio venta:',
            'descuento' => 'Descto comercial:',

        ];
    }
}
