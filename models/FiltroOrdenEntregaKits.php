<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroOrdenEntregaKits extends Model
{        
   
    public $ordenkits;
    public $presentacion;
    public $nombre_kits;
    public $fecha_inicio;
    public $fecha_corte;

    public function rules()
    {
        return [  
           [['ordenkits','presentacion','nombre_kits'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'ordenkits' => 'Entregade kits logistica:',
            'presentacion' => 'Nombre de kits:',
            'nombre_kits' => 'Presentacion del producto:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
        ];
    }
    
}