<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaRecibo extends Model
{        
   
    public $numero;
    public $cliente;
    public $tipo_recibo;
    public $desde;
    public $hasta;
    public $banco;
    public $municipio;
    public $vendedores;
    public $documento;
    public $recibo_detalle;


    public function rules()
    {
        return [  
          
            [['numero','tipo_recibo','vendedores','recibo_detalle'], 'integer'],
            [['banco','municipio','documento','cliente'], 'string'],
            [['desde','hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero recibo:',
            'cliente' => 'Cliente:',
            'tipo_recibo' => 'Tipo recibo:',
            'banco' => 'Entidad bancaria:',
            'municipio' => 'Municipio de pago:',
            'desde' => 'Fecha inicio:',
            'hasta' => 'Fecha corte:',
            'vendedores' => 'Agente comercial:',
            'documento' => 'Nit / Cedula:',
            'recibo_detalle' => 'recibo_detalle',

        ];
    }
    
}
