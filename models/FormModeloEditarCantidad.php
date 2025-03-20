<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloEditarCantidad extends Model
{
    public $cantidad;
    public $observacion;
    public $motivo;

    public function rules()
    {
        return [

           [['cantidad','motivo'], 'integer'],
            [['observacion'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cantidad' => 'Nueva cantidad:',
            'motivo' => 'Motivo dian:',
            'observacion' => 'Observacion:',

        ];
    }
}
