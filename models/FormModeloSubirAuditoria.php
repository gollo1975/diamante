<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormModeloSubirAuditoria extends Model
{
    public $observacion;  
    public $continua;
    public $condiciones;
   
    public function rules()
    {
        return [

           [['continua','condiciones'], 'integer'],
           ['observacion', 'string'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'continua' => 'Continua proceso:', 
            'condiciones' => 'Condiciones de analisis:',
            'observacion' => 'Observacion:',
            
        ];
    }
}
