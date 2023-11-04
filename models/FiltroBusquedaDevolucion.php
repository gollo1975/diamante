<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaDevolucion extends Model
{        
   
    public $cliente;
    public $numero;
    public $fecha_inicio;
    public $fecha_corte;
    public function rules()
    {
        return [  
           [['numero','cliente'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'cliente' => 'Cliente:',
            'numero' => 'Numero devolución:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',

        ];
    }
    
}