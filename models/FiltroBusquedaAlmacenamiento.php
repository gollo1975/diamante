<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaAlmacenamiento extends Model
{        
   
    public $orden;
    public $fecha_inicio;
    public $fecha_corte;
    public $lote;
    public $piso;
    public $posicion;
    public $rack;
    public $codigo;
    public $producto;
    public $proveedor;
    public $tipo_entrada;


    public function rules()
    {
        return [  
           [['orden', 'lote','piso','rack','posicion','proveedor','tipo_entrada'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
            [['producto','codigo'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'orden' => 'Numero orden:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'lote' => 'Número lote:',
            'piso' => 'Numero piso:',
            'rack' => 'Numero rack:',
            'posicion' => 'Posicion:',
            'producto' => 'Nombre producto:',
            'codigo' => 'Codigo producto',
            'proveedor' => 'Nombre proveedor:',
            'tipo_entrada' => 'Tipo entrada:',
        ];
    }
    
}