<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModeloTerminosFactura extends Model
{        
   
    public $id_inconterm;
    public $medio_transporte;
    public $ciudad_origen;
    public $ciudad_destino;
    public $peso_neto;
    public $peso_bruto;
    public $id_medida_producto;
    
   
    public function rules()
    {
        return [  
           [['id_inconterm','id_medida_producto','medio_transporte','ciudad_destino','ciudad_origen','peso_neto','peso_bruto'], 'required'],
           [['peso_neto','peso_bruto'], 'number'],
           [['ciudad_destino','ciudad_origen'], 'string'],
           [['id_inconterm','id_medida_producto','medio_transporte'], 'integer'],
           [['peso_neto','peso_bruto'], 'number'],
           [['ciudad_destino','ciudad_origen'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'id_inconterm' => 'Tipo de flete:',
            'id_medida_producto' => 'Unidad medida:',
            'medio_transporte' => 'Medio transporte:',
            'peso_neto' => 'Peso neto:',
            'peso_bruto' => 'Peso bruto',
            'ciudad_destino' => 'Ciudad destino:',
            'ciudad_origen' => 'Ciudad origen:',                    
        ];
    }
    
}