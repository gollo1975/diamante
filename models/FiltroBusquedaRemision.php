<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaRemision extends Model
{        
   
    public $numero;
    public $cliente;
    public $fecha_inicio;
    public $fecha_corte;
    public $punto_venta;
    public function rules()
    {
        return [  
           [['numero', 'cliente','punto_venta'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero de remision:',
            'punto_venta' => 'Punto de venta:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'cliente' =>'Nombre del cliente:',
           
        ];
    }
    
}