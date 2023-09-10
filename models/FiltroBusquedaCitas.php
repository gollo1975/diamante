<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaCitas extends Model
{        
   
    public $desde;
    public $hasta;
    public $vendedor;
    public $proceso;
    public $anocierre;
    public $documento;
    public $agente;
    public $presupuesto;


    public function rules()
    {
        return [  
          
            [['vendedor','proceso','anocierre','presupuesto'], 'integer'],
            [['desde','hasta'], 'safe'],
        [['agente','documento'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'desde' => 'Desde:',
            'hasta' => 'Hasta:',
            'proceso' => 'Proceso cerrado',
            'vendedor' => 'Agente comercial:',
            'anocierre' => 'Año:',
            'agente' => 'Vendedor',
            'documento' => 'Nit/Cedula:',
            'presupuesto' => 'Tipo Presupuesto:',
          

        ];
    }
    
}
