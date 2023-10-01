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
    public $tipo_cliente;
    
    public function rules()
    {
        return [  
          
            [['vendedor','activo','tipo_cliente'], 'integer'],
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
            'tipo_cliente' => 'Tipo cliente:',

        ];
    }
    
}
