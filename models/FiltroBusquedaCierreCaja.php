<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaCierreCaja extends Model
{        
   
    public $punto_venta;
    public $numero_cierre;
    public $fecha_inicio;
    public $fecha_corte;
    
    public function rules()
    {
        return [  
          
            [['punto_venta','numero_cierre'], 'integer'],
            [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'punto_venta' => 'Punto de venta:',
            'numero_cierre' => 'Numero de cierre:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',

        ];
    }
    
}
