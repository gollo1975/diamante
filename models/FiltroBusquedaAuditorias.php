<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaAuditorias extends Model
{        
   
    public $numero_auditoria;
    public $fecha_inicio;
    public $fecha_corte;
    public $etapa;
    public $numero_orden;
    public $numero_lote;
    
    public function rules()
    {
        return [  
           [['numero_auditoria', 'etapa','numero_orden','numero_lote'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero_auditoria' => 'Numero auditoria:',
            'etapa' => 'Etapa de auditoria:',
            'numero_orden' => 'Orden de produccion:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'numero_lote' =>'Numero del lote:',
        ];
    }
    
}