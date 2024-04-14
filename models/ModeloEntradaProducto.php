<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModeloEntradaProducto extends Model
{        
   
    public $codigo_producto;
    public $cantidad;
    public $producto;
   
    public function rules()
    {
        return [  
           [['cantidad'], 'integer'],
           [['codigo_producto','producto'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'cantidad' => 'Cantidad unidades:',
            'codigo_producto' => 'Codigo del producto:',
            'producto' => 'Nombre del producto:',
          
       
        ];
    }
    
}