<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaCitaProspecto extends Model
{        
   
    public $prospecto;
    public $fecha_inicio;
    public $fecha_corte;
    public $tipo_visita;
    public $vendedor;
    
    public function rules()
    {
        return [  
          
            [['prospecto','tipo_visita','vendedor'], 'integer'],
            [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'prospecto' => 'Prospecto cliente:',
            'tipo_visita' => 'Tipo visita:',
            'fecha_corte' => 'Fecha corte:',
            'fecha_inicio' => 'Fecha inicio:',
            'vendedor' => 'Agente de venta:'
       ];
    }
    
}
