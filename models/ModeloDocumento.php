<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class ModeloDocumento extends Model
{
    public $documento;

    public function rules()
    {
        return [

           [['documento'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'documento' => 'Documento produccion:',

        ];
    }
}
