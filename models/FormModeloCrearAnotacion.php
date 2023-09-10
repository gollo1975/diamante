<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */

class FormModeloCrearAnotacion extends Model
{
    public $anotacion;

    public function rules()
    {
        return [

           [['anotacion'], 'required', 'message' => 'Campo requerido'], 
           ['anotacion', 'string'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'anotacion' => 'Anotacion de cliente:',

        ];
    }
}
