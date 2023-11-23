<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaAlmacenamiento extends Model
{        
   
    public $orden;
    public $fecha_inicio;
    public $fecha_corte;
    public $lote;
    public function rules()
    {
        return [  
           [['orden', 'lote'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'orden' => 'Numero orden:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'lote' => 'Número lote:',
        ];
    }
    
}