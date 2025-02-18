<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloNuevoRecibo extends Model
{
    public $tipo_recibo;
    public $fecha_pago;
    public $banco;
    public $observacion;

    public function rules()
    {
        return [

           [['tipo_recibo','fecha_pago', 'banco'], 'required', 'message' => 'Campo requerido'], 
           [['tipo_recibo','banco'], 'integer'],
           [['observacion'], 'string'],
           ['fecha_pago', 'safe'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'tipo_recibo' => 'T. recibo:',
            'tipo_visita' => 'Banco:',
            'hora_visita' => 'Fecha pago:',
            'observacion' => 'Nota',
            'fecha_pago' => 'F. de pago:'
            

        ];
    }
}
