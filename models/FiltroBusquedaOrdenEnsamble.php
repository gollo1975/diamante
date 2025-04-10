<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaOrdenEnsamble extends Model
{        
   
    public $numero_ensamble;
    public $fecha_inicio;
    public $fecha_corte;
    public $orden;
    public $producto;
    public $numero_lote;
    public $grupo;


    public function rules()
    {
        return [  
           [['numero_ensamble', 'producto','orden','numero_lote','grupo'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero_ensamble' => 'Numero orden ensamble:',
            'producto' => 'Producto:',
            'orden' => 'Codigo orden:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'numero_lote' => 'Número lote:',
            'grupo' => 'Grupo:'
       
        ];
    }
    
}