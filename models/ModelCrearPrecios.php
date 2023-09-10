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
    public function rules()
    {
        return [  
           [['codigo', 'grupo'], 'integer'],
           [['producto'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo' => 'Codigo producto:',
            'grupo' => 'Grupo producto:',
            'producto' => 'Nombre producto:',
        ];
    }
    
}