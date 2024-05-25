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
    public $nombre_producto;
   
    public function rules()
    {
        return [  
           [['cantidad'], 'integer'],
           [['codigo_producto','nombre_producto'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'cantidad' => 'Cantidad unidades:',
            'codigo_producto' => 'Codigo del producto:',
            'nombre_producto' => 'Nombre del producto:',
          
       
        ];
    }
    
}