<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaSolicitudMateriales extends Model
{        
   
    public $numero_solicitud;
    public $fecha_inicio;
    public $fecha_corte;
    public $orden;
    public $grupo;
    public $producto;
    public $numero_lote;
    public $tipo;
    public $numero_entrega;

    public function rules()
    {
        return [  
           [['numero_solicitud', 'grupo','orden','numero_lote','tipo','numero_entrega','producto'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero_solicitud' => 'Numero solictud:',
            'grupo' => 'Grupos:',
            'orden' => 'Orde produccion:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'numero_lote' => 'Número lote:',
            'tipo' => 'Tipo material:',
            'numero_entrega' => 'Numero entrega:',
            'producto' => 'Productos:'
       
        ];
    }
    
}