<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloNuevoReciboPunto extends Model
{
    public $tipo_recibo;
    public $banco;
    public $forma_pago;
    public $valor_pago;
    public $numero_transacion;

    public function rules()
    {
        return [

           [['tipo_recibo', 'banco','forma_pago','valor_pago'], 'required', 'message' => 'Campo requerido'], 
           [['tipo_recibo','banco','forma_pago','valor_pago'], 'integer'],
           [['numero_transacion'], 'string'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'tipo_recibo' => 'Tipo recibo:',
            'tipo_visita' => 'Banco:',
            'forma_pago' => 'Forma de pago:',
            'valor_pago' => 'Valor pagado:',
            'numero_transacion' => 'Numero transacion:',
            

        ];
    }
}
