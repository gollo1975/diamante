<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class ModeloEnviarUnidadesRack extends Model
{
    public $rack;
    public $posicion;
    public $cantidad;
    public $piso;


    public function rules()
    {
        return [

           [['rack','posicion', 'cantidad', 'piso'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rack' => 'Rack:',
            'posicion' => 'Posicion:',
            'cantidad' => 'Cantidad:',
            'piso' => 'Piso:',

        ];
    }
    
}
