<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaOrdenCompra extends Model
{        
   
    public $numero;
    public $fecha_inicio;
    public $fecha_corte;
    public $solicitud;
    public $proveedor;
    public function rules()
    {
        return [  
           [['numero', 'solicitud','proveedor'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero orden:',
            'solicitud' => 'Tipo orden:',
            'proveedor' => 'Proveedor:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            
       
        ];
    }
    
}