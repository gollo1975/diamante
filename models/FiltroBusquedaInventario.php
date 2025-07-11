<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaInventario extends Model
{        
   
    public $codigo;
    public $producto;
    public $fecha_inicio;
    public $fecha_corte;
    public $grupo;
    public $inventario_inicial;
    public $busqueda_vcto;
    public $presentacion;
    public $tipo_producto;


    public function rules()
    {
        return [  
           [['codigo', 'grupo','inventario_inicial','producto','tipo_producto'], 'integer'],
           [['fecha_inicio','fecha_corte','busqueda_vcto'], 'safe'],
           ['presentacion', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo' => 'Código producto:',
            'grupo' => 'Grupo del producto:',
            'producto' => 'Nombre del producto:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'inventario_inicial' => 'Inventario inicial:',
            'busqueda_vcto' => 'Busqueda_vcto',
            'presentacion' => 'Presentacion del producto:',
            'tipo_producto' => 'Tipo de producto:',
        ];
    }
    
}