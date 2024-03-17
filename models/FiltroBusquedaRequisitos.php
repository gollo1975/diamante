<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaRequisitos extends Model
{        
   
    public $codigo;
    public $concepto;
       
    
    public function rules()
    {
        return [  
          
           [['codigo'], 'integer'],
           [['concepto'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo' => 'Codigo_',
            'concepto' => 'Nombre del requisito:',

        ];
    }
    
}
