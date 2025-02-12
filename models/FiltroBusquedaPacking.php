<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaPacking extends Model
{        
   
    public $numero_pedido;
    public $numero_packing;
    public $fecha_inicio;
    public $fecha_corte;
    public $cliente;
    public $transportadora;
    public $numero_guia;


    public function rules()
    {
        return [  
           [['numero_pedido', 'numero_packing','transportadora','numero_guia'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
            [['cliente'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero_pedido' => 'Numero de pedido:',
            'numero_packing' => 'Numero de packing:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'cliente' => 'Nombre del cliente:',
            'transportadora' => 'Transportadora:',
            'numero_guia' => 'Numero de guia:'
        ];
    }
    
}