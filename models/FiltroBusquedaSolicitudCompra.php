<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaSolicitudCompra extends Model
{        
   
    public $codigo;
    public $fecha_inicio;
    public $fecha_corte;
    public $solicitud;
    public $area;
    public function rules()
    {
        return [  
           [['codigo', 'solicitud','area'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo' => 'Código solicitud:',
            'solicitud' => 'Tipo solicitud:',
            'area' => 'Area empresa:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            
       
        ];
    }
    
}