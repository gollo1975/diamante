<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaOrdenProduccion extends Model
{        
   
    public $numero;
    public $fecha_inicio;
    public $fecha_corte;
    public $grupo;
    public $almacen;
    public $autorizado;
    public $lote;
    public function rules()
    {
        return [  
           [['numero', 'grupo','almacen','autorizado', 'lote'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero orden:',
            'grupo' => 'Grupo producto:',
            'almacen' => 'Almacen/Bodega:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'lote' => 'Número lote:',
            
       
        ];
    }
    
}