<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModelCrearPrecios extends Model
{        
   
    public $codigo;
    public $producto;
    public $grupo;
    public $presentacion;
    public $marca;
    public $proveedor;
    public $categoria;
    public $punto_venta;
    
    public function rules()
    {
        return [  
           [['codigo', 'grupo','marca','proveedor','categoria','punto_venta','producto'], 'integer'],
           [['presentacion'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo' => 'Codigo producto:',
            'grupo' => 'Grupo producto:',
            'presentacion' => 'Presentacion del producto:',
            'producto' => 'Nombre producto',
            'punto_venta' => 'Punto de venta:',
            'proveedor' => 'Proveedor:',
            'categoria' => 'Categoria:',
            'marca' => 'Marca:',
        ];
    }
    
}