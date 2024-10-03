<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormIncapacidadSeguimiento extends Model
{        
   
    public $id_incapacidad;
    public $nota;
    public $usuariosistema;
    
    
    public function rules()
    {
        return [            
           [['id_incapacidad', 'nota'], 'required'],
            [['usuariosistema'], 'string', 'max' => 30],
            [['nota'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'id_incapacidad' => 'Id Incapacidad',
            'nota' => 'Nota',
            'usuariosistema'=>'Usuario',
       
        ];
    }
    
}
