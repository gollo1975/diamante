<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaProveedor extends Model
{        
   
    public $nitcedula;
    public $nombre_completo;
    public $activo;
    public $vendedor;
    
    public function rules()
    {
        return [  
          
            [['vendedor','activo'], 'integer'],
            [['nitcedula','nombre_completo'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'nitcedula' => 'Documento:',
            'nombre_completo' => 'Razon social:',
            'activo' => 'Activo:',
            'vendedor' => 'Agente comercial:',

        ];
    }
    
}
