<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//este proceso sirve para subir el documento de produccion y subir las cantidades despachas
class ModeloMoverPosicionRack extends Model
{
    public $rack_anterior;
    public $nuevo_rack;
    public $posicion_anterior;
    public $nueva_posicion;
    public $nuevo_piso;

    public function rules()
    {
        return [

           [['rack_anterior','nuevo_rack','posicion_anterior','nueva_posicion','nuevo_piso'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rack_anterior' => 'Rack anterior:',
            'nuevo_rack' => 'Nuevo rack:',
            'posicion_anterior' => 'Posicion anterior:',
            'nueva_posicion' => 'Nueva posicion:',
            'nuevo_piso' => 'Nuevo piso:',

        ];
    }
}
