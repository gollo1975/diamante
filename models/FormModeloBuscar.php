<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormModeloBuscar extends Model
{
    public $q;   
    public $nombre;
    public $observacion;

    public function rules()
    {
        return [

            [['q','nombre'], 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan números y letras'],  
            [['observacion'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'q' => 'Dato a Buscar:',  
            'nombre' =>'Producto:',
            'observacion' => 'Observacion:',

        ];
    }
}
