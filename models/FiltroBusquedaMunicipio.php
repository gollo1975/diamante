<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaMunicipio extends Model
{        
   
    public $codigo_municipio;
     public $municipio;
    public $departamento;
    
    public function rules()
    {
        return [  
          
           [['codigo_municipio','municipio', 'departamento'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo_municipio' => 'Codigo:',
            'municipio' => 'Municipio:',
            'departamento' => 'Departamento:',

        ];
    }
    
}
