<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaPedidos extends Model
{        
   
    public $numero_pedido;
    public $documento;
    public $fecha_inicio;
    public $fecha_corte;
    public $cliente;
    public $facturado;
    public $vendedor;
    public $pedido_cerrado;
    public $presupuesto;


    public function rules()
    {
        return [  
          
            [['numero_pedido','facturado','vendedor','pedido_cerrado','presupuesto'], 'integer'],
            [['cliente','documento'], 'string'],
            [['fecha_inicio', 'fecha_corte'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'documento' => 'Documento:',
            'cliente' => 'Nombre del cliente:',
            'vendedor' => 'Agente comercial:',
            'pedido_cerrado' => 'Pedido cerrado:',
            'numero_pedido' => 'Numero pedido:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'facturado' => 'Facturado:',
            'presupuesto' => 'Presupuesto:',

        ];
    }
    
}
