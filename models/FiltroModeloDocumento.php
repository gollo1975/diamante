<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroModeloDocumento extends Model
{        
   
    public $cantidad;
    public $valor;
    public $porcentaje;


    public function rules()
    {
        return [  
          
            [['cantidad','valor'], 'integer'],
            ['porcentaje','number'],
        
        ];
    }

    public function attributeLabels()
    {
        return [   
            'cantidad' => 'Cantidades:',
            'valor' => 'Valor unitario:',
            'porcentaje' => '% Retención:',
           
        ];
    }
    
}
