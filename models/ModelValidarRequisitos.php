<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModelValidarRequisitos extends Model
{        
   
    public $documento;
    
    public function rules()
    {
        return [  
          
            [['documento'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'documento' => 'Digite el documento:',
        ];
    }
    
}
