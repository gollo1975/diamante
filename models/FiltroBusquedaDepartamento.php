<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaDepartamento extends Model
{        
   
    public $codigo_departamento;
    public $departamento;
    
    public function rules()
    {
        return [  
          
           [['codigo_departamento','departamento'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo_departamento' => 'Codigo:',
            'departamento' => 'Departamento:',

        ];
    }
    
}
