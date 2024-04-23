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
    public $responsable;
    public $peso_neto;
    public $cosmetica;




    public function rules()
    {
        return [

           [['continua','condiciones','cosmetica'], 'integer'],
           [['observacion','responsable','peso_neto'], 'string'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'continua' => 'Continua proceso:', 
            'condiciones' => 'Condiciones de analisis:',
            'observacion' => 'Observacion:',
            'peso_neto' =>'Peso neto:',
            'responsable' => 'Responsable:',
            'cosmetica' => 'Forma cosmetica:',
            
            
        ];
    }
}
