<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//este proceso sirve para subir el documento de produccion y subir las cantidades despachas
class ModeloTallasColores extends Model
{
    public $id_talla;
    public $id_color;
    public $cantidad;

    public function rules()
    {
        return [

           [['id_talla','cantidad','id_color'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_talla' => 'Nombre de talla:',
            'cantidad' => 'Cantidad:',
            'id_color' => 'Colores:',
         

        ];
    }
}
