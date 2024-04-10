<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModeloTrasladoPuntoventa extends Model
{
    public $punto_venta;  
    
    
    public function rules()
    {
        return [
           [['punto_venta'],'required',  'message' => 'Campo requerido'],
           [['punto_venta'], 'integer'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'punto_venta' => 'Punto de venta:', 
            
        ];
    }
}
