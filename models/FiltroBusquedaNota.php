<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaNota extends Model
{        
   
    public $numero;
    public $cliente;
    public $motivo;
    public $desde;
    public $hasta;
    public $documento;
    public $factura;


    public function rules()
    {
        return [  
          
            [['numero','motivo','factura'], 'integer'],
            [['documento','cliente'], 'string'],
            [['desde','hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero nota:',
            'cliente' => 'Cliente:',
            'motivo' => 'Motivo dian:',
            'desde' => 'Fecha inicio:',
            'hasta' => 'Fecha corte:',
            'documento' => 'Nit/Cedula:',
            'factura' => 'Numero factura:',

        ];
    }
    
}
