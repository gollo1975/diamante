<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaBancos extends Model
{        
   
    public $codigo_banco;
    public $banco;
   
    public function rules()
    {
        return [  
           [['codigo_banco','banco'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo_banco' => 'Codigo banco:',
            'banco' => 'Entidad bancaria:',
            
            
       
        ];
    }
    
}
