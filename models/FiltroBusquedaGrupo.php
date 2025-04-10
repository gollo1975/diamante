<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaGrupo extends Model
{        
   
    public $grupo;
    public $nombre;
       
    public function rules()
    {
        return [  
           [['grupo'], 'integer'],
           [['nombre'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'grupo' => 'Grupo:',
            'nombre' => 'Nombre del producto:',
       
        ];
    }
    
}
