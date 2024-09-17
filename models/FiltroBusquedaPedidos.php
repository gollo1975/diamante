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
    public $saldo;
    public $numero_factura;
    public $pedido_anulado;
    public $punto_venta;


    public function rules()
    {
        return [  
          
            [['numero_pedido','facturado','vendedor','pedido_cerrado','presupuesto','saldo','numero_factura','pedido_anulado','punto_venta'], 'integer'],
            [['cliente','documento'], 'string'],
            [['fecha_inicio', 'fecha_corte'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'documento' => 'Documento del cliente:',
            'cliente' => 'Nombre del cliente:',
            'vendedor' => 'Agente comercial:',
            'pedido_cerrado' => 'Pedido cerrado:',
            'numero_pedido' => 'Numero pedido:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'facturado' => 'Facturado:',
            'presupuesto' => 'Presupuesto:',
            'saldo' => 'Cartera:',
            'numero_factura' => 'Numero factura:',
            'pedido_anulado' => 'Pedido anulado:',
            'punto_venta' => 'Punto de venta:',

        ];
    }
    
}
