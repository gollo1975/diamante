<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaCompraAuditada extends Model
{        
   
    public $numero;
    public $fecha_inicio;
    public $fecha_corte;
    public $tipo;
    public $proveedor;
    public $tipo_busqueda;
    public function rules()
    {
        return [  
           [['numero', 'tipo','proveedor','tipo_busqueda'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero orden:',
            'tipo' => 'Tipo orden:',
            'proveedor' => 'Proveedor:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'tipo_busqueda' => 'Detalle del excel',
            
       
        ];
    }
    
}