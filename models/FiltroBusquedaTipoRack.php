<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaTipoRack extends Model
{        
   
    public $numero;
     public $descripcion;
    public $estado;
    public $piso;
    
    
    public function rules()
    {
        return [  
          
           [['numero','piso'], 'integer'],
           [['descripcion','estado'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'numero' => 'Numero rack:',
            'descripcion' => 'Descricion:',
            'estado' => 'Activo:',
            'piso' => 'Numero piso',

        ];
    }
    
}
